<h1><?= the_project("title") ?></h1>

<table>
  <tbody>
    <?php foreach(the_project() as $key => $value) :?>
    <tr>
      <td><?= $key ?></td>
      <td><?= $value ?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>