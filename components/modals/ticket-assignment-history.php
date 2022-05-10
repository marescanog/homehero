<?php 
    $level = isset($_POST['level']) ? $_POST['level'] : '.';
    $current = isset($_POST['data']) && isset($_POST['data']['current']) ? $_POST['data']['current']: 'None';
    $previous = isset($_POST['data']) && isset($_POST['data']['previous']) ? $_POST['data']['previous']: 'None';
    $date = isset($_POST['data']) && isset($_POST['data']['date']) ? $_POST['data']['date']: 'None';
    $reason = isset($_POST['data']) && isset($_POST['data']['reason']) ? $_POST['data']['reason']: 'None';
?>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content px-3">
        <div class="modal-header d-flex flex-space-between" style="border-bottom: 0;">
            <div class="mt-1">
                <h5>Assignment Details</h5>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem 0;">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="min-width: 350px; margin-top:-1.5rem;">
            <table class="table table-sm">
                <thead>
                    <tr>
                    <th scope="col">Date Transferred</th>
                    <th scope="col">Previous Agent</th>
                    <th scope="col">Transferred To</th>
                    <th scope="col">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $current=="" ? "None": htmlentities($date);?></td>
                        <td><?php echo $previous=="" ? "None": htmlentities($previous);?></td>
                        <td><?php echo $date=="" ? "None": htmlentities($current);?></td>
                        <td><?php echo $reason=="" ? "First Assignment": htmlentities($reason);?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>