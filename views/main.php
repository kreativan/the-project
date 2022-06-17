<h1><?= the_project("title") ?></h1>

<div class="the-project-panel padding">
  <table class="the-project-table the-project-table-striped">
    <tbody>
      <?php foreach(the_project() as $key => $value) :?>
      <tr>
        <td><?= $key ?></td>
        <td><?= $value ?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>