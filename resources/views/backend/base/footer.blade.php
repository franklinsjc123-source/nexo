 <div class="progress-wrap d-flex align-items-center justify-content-center cursor-pointer h-40px w-40px position-fixed" id="progress-scroll">
     <svg class="progress-circle w-100 h-100 position-absolute" viewBox="0 0 100 100">
         <circle cx="50" cy="50" r="45" class="progress" />
     </svg>
     <i class="ri-arrow-up-line fs-16 z-1 position-relative text-primary"></i>
 </div>
 <!-- END scroll top -->

 <!-- Begin Footer -->
 <footer class="footer">
     <div class="container-fluid">
         <center>

            {{-- <div class="d-flex justify-content-between align-items-center gap-2"> --}}
             <script>
                 document.write(new Date().getFullYear())
             </script> © NEXO CART
         {{-- </div> --}}
         </center>
     </div>
 </footer>
 <!-- END Footer -->

 <!-- Delete Source -->
		<div class="modal fade" id="deleteModal" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-body">
						<div class="text-center">
							<h4 class="mb-2">Delete Confirmation</h4>
							<p class="mb-0">
								Are you sure you want to delete..?
							</p>
							<input type="hidden" name="" id="uid" />
							<input type="hidden" name="" id="model" />

							<div class="d-flex align-items-center justify-content-center mt-4">
								<a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<a href="javascript:void(0)" onclick="deleteRow()" class="btn btn-danger">Yes, Delete it</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Delete Source -->

        <!-- Status Modal-->
         <div class="modal fade" id="statusModal" role="dialog">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-body">
						<div class="text-center">
							<h4 class="mb-2">Status Confirmation</h4>
							<p class="mb-0">
								Are you sure you want to change status..?
							</p>
							<input type="hidden" name="" id="uid_d" />
							<input type="hidden" name="" id="model_d" />
							<input type="hidden" name="" id="status" />
							<div class="d-flex align-items-center justify-content-center mt-4">
								<a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
								<a href="javascript:void(0)" onclick="change()" class="btn btn-danger">Yes, Change it</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

 </div>
 <!-- End Begin page -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr(".date-format", {
    dateFormat: "d/m/Y"
});

$(document).on('shown.bs.modal', '.modal', function () {
    flatpickr($(this).find('.date-format'), {
        dateFormat: "d/m/Y",
        allowInput: true
    });
});
</script>




 <!-- JAVASCRIPT -->
 <script src="<?= asset('backend_assets') ?>/libs/swiper/swiper-bundle.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/libs/simplebar/simplebar.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/js/scroll-top.init.js"></script>

 <script src="<?= asset('backend_assets') ?>/js/jquery.validate.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/js/select2.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/libs/sweetalert2/sweetalert2.min.js"></script>
 <script src="<?= asset('backend_assets') ?>/libs/air-datepicker/air-datepicker.js"></script>
 <!--datatable js-->
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="<?= asset('backend_assets') ?>/libs/pdf/pdfmake.js"></script>
<script src="<?= asset('backend_assets') ?>/libs/pdf/vfs_fonts.js"></script>

 <!-- App js -->
 <script type="module" src="<?= asset('backend_assets') ?>/js/app.js"></script>
 <script>
     const ToastMixin = Swal.mixin({
         toast: true,
         position: "top-end",
         showConfirmButton: false,
         timer: 2500,
         timerProgressBar: true,
         didOpen: (toast) => {
             toast.onmouseenter = Swal.stopTimer;
             toast.onmouseleave = Swal.resumeTimer;
         }
     });

	 const localeEn = {
    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
    months: ['January','February','March','April','May','June', 'July','August','September','October','November','December'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    today: 'Today',
    clear: 'Clear',
     dateFormat: 'dd-MM-yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 0
}


	 new AirDatepicker('#range-picker', {
			range: true,
			multipleDatesSeparator: ' - ',
			 locale: localeEn,
		});



     function showToast(type, message) {
         ToastMixin.fire({
             icon: type,
             title: message
         });
     }
     $(document).ready(function() {
		  $('.select2').select2();

		   dt = $('.datatable').DataTable({
				dom: 'frtip',
			});


			$('#refreshBtn').click(function (e) {
				e.preventDefault();
				location.reload();
			});



     });



     function commonDelete(id,model)
			  {
					$('#uid').val(id);
					$('#model').val(model);
					$('#deleteModal').modal('show');
			  }

			  function deleteRow()
			  {
	             var uid     =		$('#uid').val();
	             var model   =		$('#model').val();
				   var url = '<?= route('commonDelete') ?>';
					$.post(url, {
						id: uid,
						model: model,
						'_token': '<?= csrf_token() ?>'
					}, function(data) {
						if (data == 1) {
							window.location.reload();
						}
					});
			  }


			  function change()
			  {
	             var uid     = $('#uid_d').val();
    			 var model   = $('#model_d').val();
	             var status  =		$('#status').val();
				   var url = '<?= route('updateCommonStatus') ?>';
					$.post(url, {
						id: uid,
						status: status,
						model: model,
						'_token': '<?= csrf_token() ?>'
					}, function(data) {
						if (data == 1) {
							window.location.reload();
						}
					});
			  }

               function changeStatus(id, status, model) {
					 $('#uid_d').val(id);
					 $('#model_d').val(model);
					$('#status').val(status);
					$('#statusModal').modal('show');
			  }


 </script>


<script>


window.addEventListener("load", function () {
    document.getElementById("globalLoader").style.display = "none";
});

$(document).ready(function () {
    initColumnSearchDataTable(document);
});

    let dataTables = {};
    function initColumnSearchDataTable(context = document) {
         $(context).find(".colum-search").each(function () {

            let tableId = $(this).attr("id");
            let actionColIndex = -1;

            $("#" + tableId + " thead tr:first th").each(function (i) {
                if ($(this).text().trim().toLowerCase() === 'action') {
                    actionColIndex = i;
                }
            });

            dataTables[tableId] = $("#" + tableId).DataTable({
                dom: '<"row"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                orderCellsTop: true,
                fixedHeader: true,
                destroy: true,

                columnDefs: actionColIndex !== -1 ? [{
                    targets: actionColIndex,
                    searchable: false,
                    orderable: false
                }] : [],

                buttons: [
                    { extend: 'excelHtml5', title: tableId },
                    {
                        extend: 'pdfHtml5',
                        title: tableId,
                        orientation: 'landscape',
                        pageSize: 'A4'
                    }
                ],

                initComplete: function () {
                    $('.dt-buttons').hide();
                }
            });

            /* 🔍 Column search */
            if ($("#" + tableId + " thead tr.search-row").length === 0) {

                let clone = $("#" + tableId + " thead tr:eq(0)")
                    .clone()
                    .addClass("search-row");

                clone.find("th").each(function (index) {
                    if (index === actionColIndex) {
                        $(this).html('');
                    } else {
                        $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search">');
                    }
                });

                $("#" + tableId + " thead").append(clone);
            }

            dataTables[tableId].columns().every(function (i) {
                if (i === actionColIndex) return;

                $("#" + tableId + " thead tr.search-row th input")
                    .eq(i)
                    .on("keyup change", function () {
                        dataTables[tableId].column(i).search(this.value).draw();
                    });
            });
        });

        /* ✅ Excel export – table specific */
        $(document).on("click", ".excelBtn", function () {
            let tableId = $(this).data("table");
            dataTables[tableId].button(0).trigger();
        });
        $(document).on("click", ".pdfBtn", function () {
            let tableId = $(this).data("table");
            dataTables[tableId].button(1).trigger();
        });
    }



</script>


 <script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentUrl = window.location.href;

        document.querySelectorAll('.pe-nav-link').forEach(function(link) {
            if(link.href === currentUrl) {
                link.classList.add('active');

                // Expand parent collapse if inside a submenu
                const collapseParent = link.closest('.collapse');
                if(collapseParent) {
                    collapseParent.classList.add('show');
                }
            }
        });
    });
</script>
<script>
function commonCheckExist(element, table, column, value, id = null) {

    $.ajax({
        url: "{{ route('checkExist') }}",
        type: "POST",
        data: {
            table: table,
            column: column,
            value: value,
            id: id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {

            let errorSpan = $(element).next(".error-message");
            errorSpan.html("");

            if(res.status === false){
                errorSpan.html(res.message);
                element.value="";
            }
        }
    });
}

</script>

 @include('backend.alert')
 </body>



</html>
