import AOS from "aos";
import "aos/dist/aos.css";

AOS.init();

document.addEventListener("DOMContentLoaded", () => {
    // ======================
    // Toggle menu hamburger
    // ======================
    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    if (toggle && menu) {
        toggle.addEventListener("click", () => menu.classList.toggle("hidden"));
    }

    // ======================
    // Greeting berdasarkan jam
    // ======================
    const updateGreeting = () => {
        const greetingEl = document.getElementById("greeting");
        const iconEl = document.getElementById("greeting-icon");
        if (greetingEl && iconEl) {
            const hour = new Date().getHours();
            let greeting = "", icon = "";
            if (hour >= 5 && hour < 11) { greeting = "Selamat Pagi"; icon = "☀️"; }
            else if (hour >= 11 && hour < 15) { greeting = "Selamat Siang"; icon = "🌤️"; }
            else if (hour >= 15 && hour < 18) { greeting = "Selamat Sore"; icon = "🌇"; }
            else { greeting = "Selamat Malam"; icon = "🌙"; }
            greetingEl.textContent = greeting;
            iconEl.textContent = icon;
        }
    };
    updateGreeting();
    setInterval(updateGreeting, 60000);

    // ======================
    // Dropdown user dengan fade
    // ======================
    const userMenuToggle = document.getElementById("greeting-icon");
    const userDropdown = document.getElementById("user-dropdown");
    if (userMenuToggle && userDropdown) {
        const showDropdown = () => {
            userDropdown.classList.remove("hidden");
            setTimeout(() => userDropdown.classList.add("opacity-100"), 10);
        }
        const hideDropdown = () => {
            userDropdown.classList.remove("opacity-100");
            setTimeout(() => userDropdown.classList.add("hidden"), 200);
        }
        userMenuToggle.addEventListener("click", () => {
            if (userDropdown.classList.contains("hidden")) showDropdown();
            else hideDropdown();
        });
        document.addEventListener("click", (e) => {
            if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) hideDropdown();
        });
    }

    // ======================
    // Logout confirm
    // ======================
    const logout = (btnId, formId) => {
        const btn = document.getElementById(btnId);
        const form = document.getElementById(formId);
        if (btn && form) {
            btn.addEventListener("click", () => {
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Anda akan keluar dari akun ini",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Iya, keluar",
                    cancelButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        }
    }
    logout("logout-btn", "logout-form");
    logout("logout-btn-mobile", "logout-form-mobile");

    // ======================
    // Toggle revenue visibility
    // ======================
    function setupToggle(toggleId, amountId, eyeIconId) {
        const toggleBtn = document.getElementById(toggleId);
        const amountEl = document.getElementById(amountId);
        const eyeIcon = document.getElementById(eyeIconId);
        if (!toggleBtn || !amountEl || !eyeIcon) return;
        const actualAmount = amountEl.textContent;
        const maskedAmount = 'Rp ******';

        toggleBtn.addEventListener('click', () => {
            if (amountEl.textContent === actualAmount) {
                amountEl.textContent = maskedAmount;
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.393m1.77-1.77A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.06 10.06 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                `;
            } else {
                amountEl.textContent = actualAmount;
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });
    }
    setupToggle('toggle-revenue-visibility', 'revenue-amount', 'eye-icon');
    setupToggle('toggle-total-revenue-visibility', 'total-revenue-amount', 'eye-icon-total');
    setupToggle('toggle-monthly-revenue-visibility', 'monthly-revenue-amount', 'eye-icon-monthly');

    // ======================
    // BOOKING (kursi realtime)
    // ======================
    const jadwalSelect = document.getElementById('jadwal_id');
    const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
    const bookingForm = document.getElementById('booking-form');

    if (jadwalSelect) {
        jadwalSelect.addEventListener('change', function () {
            const jadwalId = this.value;

            if (jadwalId) {
                fetch(`/jadwal/${jadwalId}/seats`)
                    .then(res => res.json())
                    .then(bookedSeats => {
                        seatCheckboxes.forEach(cb => {
                            const seatDiv = cb.nextElementSibling;
                            if (bookedSeats.includes(cb.value)) {
                                cb.disabled = true;
                                cb.checked = false;
                                seatDiv.className = "w-16 h-16 flex items-center justify-center rounded bg-red-500 text-white cursor-not-allowed";
                            } else {
                                cb.disabled = false;
                                seatDiv.className = "w-16 h-16 flex items-center justify-center rounded bg-green-500 text-white hover:bg-blue-500";
                            }
                        });
                    });
            } else {
                // reset ke default abu-abu
                seatCheckboxes.forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                    cb.nextElementSibling.className = "w-16 h-16 flex items-center justify-center rounded bg-gray-300 text-black";
                });
            }
        });
    }

    // highlight kursi saat dipilih
    seatCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const seatDiv = this.nextElementSibling;
            if (this.checked) {
                seatDiv.classList.add("ring-4", "ring-yellow-400");
            } else {
                seatDiv.classList.remove("ring-4", "ring-yellow-400");
            }
        });
    });

    // validasi minimal pilih 1 kursi
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.seat-checkbox:checked').length;
            if (checked === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih minimal 1 kursi!',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // ======================
    // Counter animation
    // ======================
    function animateCounter(el) {
        const target = +el.getAttribute("data-target");
        const suffix = el.getAttribute("data-suffix") || "";
        let current = 0;
        const increment = Math.ceil(target / 100);
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.textContent = target + suffix;
                clearInterval(timer);
            } else {
                el.textContent = current + suffix;
            }
        }, 30);
    }

    const statsSection = document.getElementById("stats-section");
    if (statsSection) {
        const counters = statsSection.querySelectorAll(".counter");
        let started = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !started) {
                    counters.forEach(counter => animateCounter(counter));
                    started = true;
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statsSection);
    }
});
