<footer style="padding: 6rem 5% 2rem; background: var(--primary); color: var(--white); margin-top: 4rem;">
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 4rem; margin-bottom: 4rem;">
        <div>
            <h3 style="color: var(--accent); margin-bottom: 1.5rem;">Urban Grind</h3>
            <p style="opacity: 0.7; font-size: 0.9rem;">Elevating your daily ritual through artisan coffee and thoughtfully designed spaces in the heart of Mumbai.</p>
        </div>
        <div>
            <h4 style="margin-bottom: 1.5rem;">Hours</h4>
            <p style="opacity: 0.7; font-size: 0.9rem;">Mon - Fri: 8am - 11pm<br>Sat - Sun: 8am - 12am</p>
        </div>
        <div>
            <h4 style="margin-bottom: 1.5rem;">Location</h4>
            <p style="opacity: 0.7; font-size: 0.9rem;">Plot 45, Perry Road<br>Bandra West<br>Mumbai, MH 400050</p>
        </div>
        <div>
            <h4 style="margin-bottom: 1.5rem;">Follow Us</h4>
            <div style="display: flex; gap: 1rem;">
                <span style="font-size: 1.2rem; cursor:pointer;">Instagram</span>
                <span style="font-size: 1.2rem; cursor:pointer;">Twitter</span>
            </div>
        </div>
    </div>
    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; text-align: center; font-size: 0.8rem; opacity: 0.5;">
        &copy; <?php echo date('Y'); ?> Urban Grind Café Mumbai. All Rights Reserved.
    </div>
</footer>

<script>
    // Smooth Page Load
    window.addEventListener('load', () => {
        document.body.style.transition = 'opacity 0.6s ease';
        document.body.style.opacity = '1';
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (nav && window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else if(nav) {
            nav.classList.remove('scrolled');
        }
    });

    // Scroll Reveal Logic
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-up').forEach(el => {
        revealObserver.observe(el);
    });

    // Dynamic Filtering for Menu
    const filterBtns = document.querySelectorAll('.filter-btn');
    const menuCards = document.querySelectorAll('.menu-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                b.style.background = 'white';
                b.style.color = 'var(--text-dark)';
            });
            btn.style.background = 'var(--accent)';
            btn.style.color = 'white';

            const filter = btn.getAttribute('data-category');

            menuCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Interactive Card Tilt Effect
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 25;
            const rotateY = (centerX - x) / 25;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
</script>
</body>
</html>