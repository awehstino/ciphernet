
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/dashboard.css">
    <title>Ciphernet</title>
</head>
<body>
    <header>
        <div class="header_container">
            
         <div class="nav">
            
             <input type="checkbox" id="nav-check">
  <div class="nav-header">
    <h2 class="title">Ciphernet Timesheet</h2>
  </div>
  <div class="nav-btn">
    <label for="nav-check">
      <span></span>
      <span></span>
      <span></span>
    </label>
  </div>
  
  <div class="nav-links">
   
    <a href="<?= base_url('/coreupdates') ?>">Core updates</a>
    <a href="<?= base_url('/otherupdates') ?>">Other updates</a>
    <a href="<?= base_url('/create_project') ?>">Add Project</a>
    <a href="<?= base_url('/admin_logout') ?>" class="logout">Log Out</a>
    
    
</div>
</div>
<div class="profile">
<?php if (session()->get('isLoggedIn')): ?>
        <h5><?= session()->get('fullname') ?>!</h5>
        <h3>Welcome back, Administrator</h3>
    <?php endif; ?>
</div>

</div>
        </div>
    </header>
    <!-- header section end -->
    <!-- main section start -->
     <div class="main_container">
      
        <div class="total_project">
            <div class="title_project">
                <h2>Total Projects</h2>
            </div>
            <h1 class="total"><?= $projectslen['total'] ?></h1>
        </div>

    <div class="flexbox">
         <div class="total_inprogress">
            <div class="title_head">
                <h2>pending</h2>
            </div>
            <h1 class="inprogress"><?= $projectslen['pending'] ?></h1>
        </div>

        <div class="total_pending">
            <div class="title_head">
                <h2>inprogress</h2>
            </div>
            <h1 class="pending"><?= $projectslen['inprogress'] ?></h1>
        </div>
        <div class="total_complete">
            <div class="title_head">
                <h2>complete</h2>
            </div>
            <h1 class="complete"><?= $projectslen['completed'] ?></h1>
        </div>
    </div>
       
     </div>
     <div class="projects_header_title">
        <h2>All Projects</h2>
     </div>
     
     <div class="projects_container"  >
        <?php   foreach ($projectslen['allProjects'] as $project): ?>
            <div class="projectbox">
            <h4><?=   $project["project_name"] ?> </h4>
            <div class="desc"><?=   $project["project_desc"] ?> </div >
            <div class="updates">
            <?php
                $commentsCount = 0;
                if (!empty($projectslen["allcomments"])) {
                    foreach ($projectslen["allcomments"] as $comment) {
                        if ($comment["project_id"] === $project["project_id"]) {
                            $commentsCount++;
                        }
                    }
                }
                ?>
                <?php if ($commentsCount > 0): ?>
                    <div>Updates (<?= $commentsCount ?>)</div>
                <?php else: ?>
                    <div>No updates yet</div>
                <?php endif; ?>
      </div >
  </div>
        
       <?php endforeach; ?>
    
     </div>
    
</body>
</html>