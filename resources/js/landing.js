// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Navigation scroll effect
let lastScroll = 0;
const nav = document.querySelector('nav');
window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    if (currentScroll > 100) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
    lastScroll = currentScroll;
});

// Mobile menu toggle
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// FAQ Accordion
function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        if (question) {
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                faqItems.forEach(i => i.classList.remove('active'));
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });
}

// Initialize FAQ when FAQs are loaded
document.addEventListener('DOMContentLoaded', () => {
    // Check if FAQs are already loaded
    setTimeout(() => {
        initFAQ();
    }, 500);
    
    // Also initialize after FAQs are loaded via fetch
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args).then(response => {
            if (args[0] && args[0].includes('/faqs')) {
                setTimeout(() => {
                    initFAQ();
                }, 100);
            }
            return response;
        });
    };
});

// Form validation
const inquiryForm = document.getElementById('inquiry-form');
if (inquiryForm) {
    inquiryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const requestNumber = document.getElementById('request_number').value.trim();
        const resultDiv = document.getElementById('inquiry-result');
        
        if (!requestNumber) {
            resultDiv.innerHTML = '<div class="bg-red-100 text-red-700 p-4 rounded-lg">يرجى إدخال رقم الطلب</div>';
            return;
        }
        
        resultDiv.innerHTML = '<div class="text-center text-blue-600 p-4">جاري البحث...</div>';
        
        fetch(`/inquiry?request_number=${requestNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    resultDiv.innerHTML = `<div class="bg-red-100 text-red-700 p-4 rounded-lg">${data.error}</div>`;
                } else {
                    const statusText = {
                        'new': 'جديد',
                        'under_review': 'قيد المراجعة',
                        'approved': 'مقبول',
                        'rejected': 'مرفوض'
                    };
                    const statusColors = {
                        'new': 'bg-gray-100 text-gray-800 border-gray-300',
                        'under_review': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'approved': 'bg-green-100 text-green-800 border-green-300',
                        'rejected': 'bg-red-100 text-red-800 border-red-300'
                    };
                    const statusColor = statusColors[data.status] || 'bg-gray-100 text-gray-800 border-gray-300';
                    
                    resultDiv.innerHTML = `
                        <div class="${statusColor} border-2 p-4 rounded-lg fade-in">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ${statusColor}">
                                    ${statusText[data.status] || data.status}
                                </span>
                            </div>
                            <p class="mb-2"><strong>رقم الطلب:</strong> ${data.request_number}</p>
                            <p class="mb-2"><strong>اسم الشركة:</strong> ${data.company_name}</p>
                            <p class="mb-2"><strong>تاريخ الإنشاء:</strong> ${data.created_at}</p>
                            ${data.rejection_reasons && data.rejection_reasons.length > 0 ? `<p class="mt-3 p-2 bg-red-50 border border-red-200 rounded"><strong>أسباب الرفض:</strong> ${data.rejection_reasons.join('، ')}</p>` : ''}
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="bg-red-100 text-red-700 p-4 rounded-lg">حدث خطأ أثناء الاستعلام</div>';
            });
    });
}

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.feature-card, .faq-item').forEach(el => {
        observer.observe(el);
    });
});

