 <!--begin::Footer-->
 <footer class="app-footer">
     <div class="container-fluid">
         <div class="row align-items-center">
             <div class="col-md-6">
                 <strong>&copy; <?= date('Y'); ?> DEV-BLOG CMS</strong>
                 <span class="text-muted mx-2">|</span>
                 <span class="text-muted">Phiên bản 1.0.0</span>
             </div>
             <div class="col-md-6 text-md-end">
                 <span class="text-muted">Được phát triển bởi</span>
                 <a href="https://github.com/huydev14" class="footer-link" target="_blank">huydev14</a>
                 <span class="footer-social">
                     <a href="https://github.com/huydev14" target="_blank" title="GitHub">
                         <i class="bi bi-github"></i>
                     </a>
                     <a href="#" target="_blank" title="Facebook">
                         <i class="bi bi-facebook"></i>
                     </a>
                 </span>
             </div>
         </div>
     </div>
 </footer>
 <!--end::Footer-->

 </div>
 <!--end::App Wrapper-->

 <!--begin::Script-->
 <!--begin::Third Party Plugin(OverlayScrollbars)-->
 <script
     src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
     integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
     crossorigin="anonymous"></script>
 <!--end::Third Party Plugin(OverlayScrollbars)-->
 <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
 <script
     src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
     integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
     crossorigin="anonymous"></script>
 <!--end::Required Plugin(popperjs for Bootstrap 5)-->
 <!--begin::Required Plugin(Bootstrap 5)-->
 <script
     src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
     integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
     crossorigin="anonymous"></script>
 <!--end::Required Plugin(Bootstrap 5)-->
 <!--begin::Required Plugin(AdminLTE)-->
 <script src="<?= PUBLIC_URL ?>/assets/js/adminlte.js"></script>
 <!--end::Required Plugin(AdminLTE)-->

 <!--begin::OverlayScrollbars Configure-->
 <script>
     const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
     const Default = {
         scrollbarTheme: 'os-theme-light',
         scrollbarAutoHide: 'leave',
         scrollbarClickScroll: true,
     };
     document.addEventListener('DOMContentLoaded', function() {
         const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
         if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
             OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                 scrollbars: {
                     theme: Default.scrollbarTheme,
                     autoHide: Default.scrollbarAutoHide,
                     clickScroll: Default.scrollbarClickScroll,
                 },
             });
         }
     });
 </script>
 <!--end::OverlayScrollbars Configure-->

 <!-- OPTIONAL SCRIPTS -->
 <!-- sortablejs -->
 <script
     src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
     integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
     crossorigin="anonymous"></script>
 <!-- sortablejs -->
 <script>
     const connectedSortables = document.querySelectorAll('.connectedSortable');
     connectedSortables.forEach((connectedSortable) => {
         let sortable = new Sortable(connectedSortable, {
             group: 'shared',
             handle: '.card-header',
         });
     });

     const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
     cardHeaders.forEach((cardHeader) => {
         cardHeader.style.cursor = 'move';
     });
 </script>

 </body>
 <!--end::Body-->

 </html>