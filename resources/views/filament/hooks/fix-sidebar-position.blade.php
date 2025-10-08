<script>
    (function(){
        let nav = document.getElementsByClassName('fi-sidebar-nav')[0];
        const scrollTop = localStorage.getItem('sidebarScrollTop');
        if (scrollTop) {
            requestAnimationFrame(()=>{
                nav.scrollTop = parseInt(scrollTop);
            })
        } else {
            nav.scrollTop = 0;
        }
        nav.addEventListener('scroll',()=>{
            localStorage.setItem('sidebarScrollTop', String(nav.scrollTop));
        })
    })()
</script>