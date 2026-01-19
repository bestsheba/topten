@livewire('product-quick-view')
@livewire('cart-drawer')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navigation', () => ({
            categories: [],
            isMenuOpen: false,
            activeCategory: null,
            mobileOpenCategory: null,

            init() {
                // Fetch categories from the server
                fetch('/navigation')
                    .then(response => response.json())
                    .then(data => {
                        this.categories = data;
                    });

                // Close dropdowns when resizing to mobile
                window.addEventListener('resize', () => {
                    if (window.innerWidth < 768) {
                        this.activeCategory = null;
                    }
                });
            },

            toggleMenu() {
                this.isMenuOpen = !this.isMenuOpen;
                // Close all mobile categories when closing menu
                if (!this.isMenuOpen) this.mobileOpenCategory = null;
            },

            setActiveCategory(categoryId) {
                // Only change if it's a different category
                if (this.activeCategory !== categoryId) {
                    this.activeCategory = categoryId;
                }
            },

            toggleMobileCategory(categoryId) {
                this.mobileOpenCategory = this.mobileOpenCategory === categoryId ? null :
                    categoryId;
            }
        }));
    });
</script>
