<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Project</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
    <?php 
$status = session()->get('status');
$statusTime = session()->get('status_time');
$displayTime = 5; 

if ($status && $statusTime && (time() - $statusTime) <= $displayTime): 
?>
   
        <div class="status">
            <?= $status ?>
        </div>
   
<?php 
    // Unset the status after it has been displayed
    session()->remove('status');
    session()->remove('status_time');
endif; 
?> 
        <h1>CipherNet Project Portal</h1>
        <div id="containers">
            <h2>Create New Project</h2>
            <form action="<?= base_url('/newproject') ?>" method="post" id="create-project-form">
            <div class="form-group">
                <label for="project-name">Project Name</label>
                <input type="text" id="project-name" name="project_name" required>
            </div>
            <div class="form-group">
                <label for="project-desc">Project Description</label>
                <textarea id="project-desc" name="project_desc" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="completed">Check if it's not a new project</label>
                <input type="checkbox" id="completed" name="completed" value="true">
            </div>
            <div class="form-group">
                <label for="execute-date">Select Date</label>
                <input type="date" id="execute-date" name="execute_date">
            </div>
            <div class="form-group">
                <label for="developers">Assign Developers</label>  
                <select id="dev" name="developers[]" class="js-multi-select" multiple>
                <?php foreach ($users as $user): ?>
                <option value="<?= htmlspecialchars($user['user_id']) ?>">
                  <?= htmlspecialchars($user['fullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            </div>
           
            
            <button type="submit">Create Project</button>
         </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-multi-select').select2({
                closeOnSelect: false,
                placeholder: "Select developers",
                allowClear: true,
                templateResult: formatState
            });

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span><input type="checkbox" class="select2-checkbox"/> ' + state.text + '</span>'
                );
                return $state;
            }
        });
    </script>
</body>
</html>
