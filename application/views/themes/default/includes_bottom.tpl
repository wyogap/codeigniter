
<!-- bootstrap. bundle includes popper.js -->
<script src="{$base_url}assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>

<script src="{$base_url}assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js" defer></script>

<!-- leaflet -->
{if !empty($use_geo)}
<script src="{$base_url}assets/leaflet/leaflet/leaflet.js"></script>
<script src="{$base_url}assets/leaflet/esri/esri-leaflet.js"></script>
<script src="{$base_url}assets/leaflet/esri/esri-leaflet-geocoder.js"></script>
<script src="{$base_url}assets/leaflet/fullscreen/Leaflet.fullscreen.min.js"></script>
<script src="{$base_url}assets/leaflet/easybutton/easy-button.js"></script>
{/if}

<script src="{$base_url}assets/select2/js/select2.min.js"></script>
<script src="{$base_url}assets/jquery-mask/jquery.mask.min.js"></script>

<!-- datatables -->
<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.js"></script>
<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Select-1.3.1/js/dataTables.select.min.js" defer></script>
<script src="{$base_url}assets/datatables/Select-1.3.1/js/select.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer></script>
<script src="{$base_url}assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>
<!--
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.print.min.js" defer></script> -->

<!-- datatables : spreadsheet like key -->
<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js" defer></script>
<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/keyTable.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/Editor-2.0.4/js/dataTables.editor.min.js"></script>
<script src="{$base_url}assets/datatables/Editor-2.0.4/js/editor.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/RowReorder-1.2.6/js/dataTables.rowReorder.js" defer></script>
<script src="{$base_url}assets/datatables/RowReorder-1.2.6/js/rowReorder.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/SearchBuilder-1.3.0/js/dataTables.searchBuilder.min.js" defer></script>
<script src="{$base_url}assets/datatables/SearchBuilder-1.3.0/js/searchBuilder.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/SearchPanes-1.4.0/js/dataTables.searchPanes.min.js" defer></script>
<script src="{$base_url}assets/datatables/SearchPanes-1.4.0/js/searchPanes.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/tcg/dt-editor-select2.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-mask.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-toggle.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-checkbox.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-cascade.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-unitprice.js" defer></script>

<script src="{$base_url}assets/datatables/tcg/dt-editor-text.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-number.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-readonly.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-date.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-textarea.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-options.js" defer></script>

{if !empty($use_editor_table)}
<script src="{$base_url}assets/datatables/tcg/dt-editor-table.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-table-select.js" defer></script>
{/if} 

{if !empty($use_editor_rowgroup)}
<script src="{$base_url}assets/datatables/tcg/dt-plugin-rowgroup.js" defer></script>
{/if}

{if !empty($use_geo)}
<script src="{$base_url}assets/datatables/tcg/dt-editor-geolocation.js" defer></script>
{/if} 

{if !empty($use_upload)}
<script src="{$base_url}assets/dropzone/dropzone.min.js"></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-upload.js" defer></script>

<!-- dragula drag-n-drop component -->
<script src="{$base_url}assets/dragula/dragula.min.js" defer></script>
{/if} 

{if !empty($use_wysiwyg)}
<!-- WYSIWYG editor -->
<script src="{$base_url}assets/ckeditor5/ckeditor.js"></script>
<!-- <script src="{$base_url}assets/backend/js/vendor/summernote-bs4.min.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.summernote.js"></script> -->
<script src="{$base_url}assets/datatables/tcg/dt-editor-editor.js" defer></script>
{/if} 

{if !empty($use_calendar)}
<!-- full calendar -->
<script src="{$base_url}assets/fullcalendar/core/main.min.js" defer></script>
{/if}

<!-- mustache templating -->
<script src="{$base_url}assets/mustache/mustache.min.js" defer></script>

<!-- toastr toast popup -->
<script src="{$base_url}assets/jquery-confirm/jquery-confirm.min.js"></script>
<script src="{$base_url}assets/toastr/toastr.min.js"></script>

<!-- fontawesome -->
<script src="{$base_url}assets/fontawesome/js/fontawesome.min.js" defer charset="utf-8"></script>
<!-- <script src="{$base_url}assets/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js" defer charset="utf-8"></script> -->

<!-- jquery plugins -->
<script src="{$base_url}assets/jquery-jvectormap/jquery-jvectormap.min.js" defer></script>
<!-- <script src="{$base_url}assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script> -->
<script src="{$base_url}assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js" defer charset="utf-8"></script>

<!--- moment -->
<script src="{$base_url}assets/moment/moment-with-locales.min.js" defer></script>

<!-- third party js -->
<!-- <script src="{$base_url}assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.dashboard.js"></script>
<script src="{$base_url}assets/backend/js/pages/datatable-initializer.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.form-wizard.js"></script> -->

<!-- app -->
<script src="{$base_url}{$theme_prefix}/js/app.min.js" defer></script>

{if !empty($use_upload)}
<script src="{$base_url}{$theme_prefix}/js/ui/component.fileupload.js" defer charset="utf-8"></script>
<script src="{$base_url}{$theme_prefix}/js/ui/component.dragula.js" defer></script>
{/if}

<script src="{$base_url}{$theme_prefix}/js/custom.js" defer></script>
<script src="{$base_url}{$theme_prefix}/app.js" defer></script>

<script type="text/javascript">
    $.fn.dataTable.ext.errMode = 'throw';
    $.extend($.fn.dataTable.defaults, {
        responsive: true,
    });
    $.extend( true, $.fn.dataTable.Editor.defaults, {
        formOptions: {
            main: {
                onBackground: 'none'
            },
            bubble: {
                onBackground: 'none'
            }
        }
    });
</script>






