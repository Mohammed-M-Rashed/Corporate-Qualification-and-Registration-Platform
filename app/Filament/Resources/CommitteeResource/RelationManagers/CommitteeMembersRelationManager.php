<?php

namespace App\Filament\Resources\CommitteeResource\RelationManagers;

use App\Enums\MemberType;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CommitteeMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'committeeMembers';

    protected static ?string $recordTitleAttribute = 'user.name';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم العضو')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('member_type')
                    ->label('نوع العضو')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        MemberType::Legal => 'blue',
                        MemberType::Technical => 'purple',
                        MemberType::Financial => 'green',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        MemberType::Legal => 'عضو قانوني',
                        MemberType::Technical => 'عضو فني',
                        MemberType::Financial => 'عضو مالي',
                        default => 'غير محدد',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.roles.name')
                    ->label('الدور')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        \Filament\Forms\Components\Select::make('member_type')
                            ->label('نوع العضو')
                            ->options([
                                MemberType::Legal->value => 'عضو قانوني',
                                MemberType::Technical->value => 'عضو فني',
                                MemberType::Financial->value => 'عضو مالي',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // عند اختيار النوع، نتحقق من وجود عضو من هذا النوع
                                $existingMember = $this->getOwnerRecord()->committeeMembers()
                                    ->whereHas('user', fn ($q) => $q->where('member_type', $state))
                                    ->first();
                                
                                if ($existingMember) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('يوجد بالفعل عضو من هذا النوع في اللجنة')
                                        ->warning()
                                        ->send();
                                    $set('member_type', null);
                                    $set('user_id', null);
                                }
                            }),
                        \Filament\Forms\Components\Select::make('user_id')
                            ->label('المستخدم')
                            ->options(function (callable $get) {
                                $memberType = $get('member_type');
                                if (!$memberType) {
                                    return [];
                                }
                                
                                $existingMemberIds = $this->getOwnerRecord()->committeeMembers()->pluck('user_id')->toArray();
                                return \App\Models\User::whereNotIn('id', $existingMemberIds)
                                    ->where('member_type', $memberType)
                                    ->whereHas('roles', fn ($q) => $q->where('name', 'Committee Member'))
                                    ->get()
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->disabled(fn (callable $get) => !$get('member_type'))
                            ->helperText('اختر نوع العضو أولاً لعرض المستخدمين المتاحين'),
                    ])
                    ->action(function (array $data): void {
                        // التحقق من وجود عضو آخر من نفس النوع
                        $user = \App\Models\User::find($data['user_id']);
                        $existingMember = $this->getOwnerRecord()->committeeMembers()
                            ->whereHas('user', fn ($q) => $q->where('member_type', $user->member_type))
                            ->first();
                        
                        if ($existingMember) {
                            throw new \Exception('يوجد بالفعل عضو من هذا النوع في اللجنة');
                        }

                        \App\Models\CommitteeMember::create([
                            'committee_id' => $this->getOwnerRecord()->id,
                            'user_id' => $data['user_id'],
                        ]);
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->action(function ($record) {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->delete();
                            }
                        }),
                ]),
            ]);
    }
}

