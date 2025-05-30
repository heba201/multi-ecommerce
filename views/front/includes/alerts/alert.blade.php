<!-- <div class="modal fade in alert-modal" style="display: none; padding-right: 17px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons close">close</i>
                </button>
            </div>
            <div class="modal-body text-center"> the product added to favourite list successfully </div>
        </div>
    </div>
</div> -->

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
    $msg=Session::get('success');
    ?>
@if(isset($msg))

    <script>
    var messageTxt = "<?php echo $msg ?>";
        // Swal.fire('', messageTxt,'success')
//         swal({
//   icon: "success",
// });
swal({
  text: messageTxt,
  icon: "success",
});
        </script>
@endif

<?php
    $warning=Session::get('warning');
    echo $warning;
    ?>
@if(isset($warning))

    <script>
    var messageTxt = "<?php echo $warning ?>";
        // Swal.fire('', messageTxt,'success')
//         swal({
//   icon: "success",
// });
swal({
  text: messageTxt,
  icon: "warning",
});
        </script>
@endif


<?php
    $error=Session::get('error');
    ?>
@if(isset($error))

    <script>
    var messageTxt = "<?php echo $error ?>";
        // Swal.fire('', messageTxt,'success')
//         swal({
//   icon: "success",
// });
swal({
  text: messageTxt,
  icon: "error",
});
        </script>
@endif


<?php
    $message=Session::get('message');
    ?>
@if(isset($message))

    <script>
    var messageTxt = "<?php echo $message ?>";
        // Swal.fire('', messageTxt,'success')
//         swal({
//   icon: "success",
// });
swal({
  text: messageTxt,
  icon: "info",
});
        </script>
@endif