<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Core Updates</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body class="p-3 m-0 border-0 bd-example m-0 border-0">
    
    <table class="table caption-top">
    <caption class="text-center">Other Updates from Developers</caption>
      <thead class="bg-primary text-white">
        <tr>
          <th scope="col"></th>
          <th scope="col">Learning</th>
          <th scope="col">Developer name</th>
          <th scope="col">Comment</th>
          <th scope="col">Location</th>
          <th scope="col">Country</th>
          <th scope="col">State</th>
          <th scope="col">City</th>
          <th scope="col">Update Date</th>
          <th scope="col">Time Spend</th>
        </tr>
      </thead>
      <tbody>
      <?php $numbers = 1;  foreach ($projectslen['allcomments'] as $comment): ?>
       
        <tr>
          <th scope="row"><?= $numbers++ ?></th>
          
          <td><?= $comment["learning"] ?></td>
          <td><?= $comment["user_fullname"] ?></td>
          <td><?= $comment["comment_text"] ?></td>
          <td><?= $comment["user_location"] ?></td>
          <td><?= $comment["user_country"] ?></td>
          <td><?= $comment["user_state"] ?></td>
          <td><?= $comment["user_city"] ?></td>
          <td><?= $comment["created_at"] ?></td>
          <td><?= $comment["timeSpend"] ?></td>
        </tr>
       
        <?php endforeach; ?>
      </tbody>
    </table>
    
  </body>
</html>
