
$(document).ready(function() {
    
    if ($('.configuration').length) {
        console.log("Configuration page detected");
        
        initConfigCarousels();
        
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            initConfigCarousels();
            
            setTimeout(function() {
                initPagination();
            }, 100);
        });
        
        $('.config-link-wrapper').on('click', function() {
            const targetId = $(this).attr('data-target');
            console.log(`Config link clicked with target: ${targetId}`);
            
            const correspondingButton = $(`button[data-bs-target="${targetId}"]`);
            if (correspondingButton.length) {
                correspondingButton.click();
                
                setTimeout(() => {
                    $('.header-navigations')[0].scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 150);
            }
        });
        
        $('.config-link-wrapper').css('cursor', 'pointer');
        
        setTimeout(function() {
            initPagination();
        }, 200);
    }
    
    function initConfigCarousels() {
        
        const activeTab = $('.tab-pane.active');
        if (!activeTab.length) return;
        
        console.log(`Active tab ID: ${activeTab.attr('id')}`);
        
        activeTab.find('.content-liste .content_list_bloc_accessoire > li').each(function() {
            $(this).css('display', 'block');
        });
        
        const carousels = activeTab.find('.content_image_list_accessoire');
        
        if (carousels.length > 0) {
            
            // Détruire et réinitialiser chaque carrousel
            carousels.each(function() {
                const carousel = $(this);
                
                if (carousel.hasClass('owl-loaded')) {
                    carousel.trigger('destroy.owl.carousel');
                    carousel.find('.owl-stage-outer').children().unwrap();
                    carousel.removeClass('owl-loaded owl-drag owl-hidden');
                }
                
                setTimeout(function() {
                    carousel.owlCarousel({
                        loop: true,
                        margin: 0,
                        nav: true,
                        dots: true,
                        responsive: {
                            0: { items: 1 },
                            600: { items: 1 },
                            1000: { items: 1 }
                        }
                    });
                }, 50);
            });
        } else {
            console.warn("No carousels found in active tab");
        }
    }
    

    function initPagination() {
        
      
        const paginationDiv = document.getElementById('pagination');
        if (!paginationDiv) {
            console.warn("Pagination element not found, skipping pagination initialization");
            return;
        }
        
        const activeTab = $('.tab-pane.active');
        if (!activeTab.length) return;
        
        const items = activeTab.find('.content_list_bloc_accessoire li').toArray();

        const itemsPerPage = 6;  
        let currentPage = 1;     
        
        // Calculer le nombre total de pages
        const totalPages = Math.ceil(items.length / itemsPerPage);
        
        function displayItems(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            
            // Cacher tous les éléments
            $(items).css('display', 'none');
            
            for (let i = start; i < end && i < items.length; i++) {
                $(items[i]).css('display', 'block');
            }
        }
        
        // Fonction pour générer les boutons de pagination
        function generatePagination() {
            paginationDiv.innerHTML = ''; 
            
            if (totalPages <= 1) {
                return;
            }
            
            // Créer les boutons de page
            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.innerText = i;
                button.classList.add('page-button');
                
                button.onclick = function() {
                    currentPage = i;
                    displayItems(currentPage);
                    updatePaginationButtons();
                };
                
                paginationDiv.appendChild(button);
            }
            updatePaginationButtons();
        }
        function updatePaginationButtons() {
            const buttons = paginationDiv.querySelectorAll('.page-button');
            buttons.forEach(button => {
                if (parseInt(button.innerText) === currentPage) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }
        
        displayItems(currentPage);
        generatePagination();
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const configLinks = document.querySelectorAll('.config_lik_group a');
    
    if (configLinks.length > 0) {
        
        configLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                setTimeout(() => {
                    const headerNav = document.querySelector('.header-navigations');
                    if (headerNav) {
                        headerNav.scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        console.error("Header navigation element not found");
                    }
                }, 50);
            });
        });
    } else {
        console.warn("No configuration links found on this page");
    }

    // Responsive header
    const contentHeader = document.querySelector('.content_header_bottom');
    const btnToggleHeader = contentHeader.querySelector('.btn_toggle_header');
    const meniLi = contentHeader.querySelectorAll('.nav-item-custome')
    btnToggleHeader.addEventListener('click', function() {
        contentHeader.classList.toggle('active');
        document.querySelector('html').classList.toggle('no-scroll');
        meniLi.forEach(item => {
            item.classList.remove('nav-item');
        });
    });

    // accordeon mobile
    const btnCaret = contentHeader.querySelectorAll('.btn_caret');
    
    console.log("btnCaret", btnCaret.length);
    
    function closeOtherMenus(currentMenu) {
        const allActiveMenus = contentHeader.querySelectorAll('.sous-menu.active');
        allActiveMenus.forEach(menu => {
            if (menu !== currentMenu) {
                const parentLi = menu.closest('.nav-item-custome');
                const caretBtn = parentLi.querySelector('.btn_caret');
                const icon = caretBtn ? caretBtn.querySelector('i') : null;
                
                if (icon) {
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
                
                menu.classList.remove('active');
            }
        });
    }
    
    btnCaret.forEach((el) => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); 
            
            const parentLi = this.closest('.nav-item-custome');
            const sousMenu = parentLi.querySelector('.sous-menu');
            
            console.log("Sous menu trouvé :", sousMenu);
            
            if (sousMenu) {
                if (sousMenu.classList.contains('active')) {
                    sousMenu.classList.remove('active');
                    
                    const icon = this.querySelector('i');
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                } 
                else {
                    closeOtherMenus(sousMenu);
                    sousMenu.classList.add('active');
                    
                    const icon = this.querySelector('i');
                    icon.classList.remove('fa-caret-down');
                    icon.classList.add('fa-caret-up');
                }
            }
        });
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.content_header_bottom') && !e.target.closest('.btn_toggle_header')) {
            contentHeader.classList.remove("active");
            document.querySelector('html').classList.remove('no-scroll');
        }
        if (!e.target.closest('.nav-item-custome')) {
            
            const allActiveMenus = contentHeader.querySelectorAll('.sous-menu.active');
            allActiveMenus.forEach(menu => {
                menu.classList.remove('active');
                const parentLi = menu.closest('.nav-item-custome');
                const caretBtn = parentLi.querySelector('.btn_caret');
                const icon = caretBtn ? caretBtn.querySelector('i') : null;
                
                if (icon) {
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
            });
           
        }
    });
});



