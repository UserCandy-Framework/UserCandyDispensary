<!-- Adds Sidebar Top Code -->
<div class='input-group mb-3' style='margin-bottom: 25px'>
  <div class='input-group-prepend'>
    <span class='input-group-text'>Adds Sidebar Top</span>
  </div>
  <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_sidebar_top', 'class' => 'form-control', 'value' => $data['adds_sidebar_top'], 'placeholder' => 'Sidebar Top Adds Code', 'rows' => '6')); ?>
</div>

<!-- Adds Sidebar Bottom Code -->
<div class='input-group mb-3' style='margin-bottom: 25px'>
  <div class='input-group-prepend'>
    <span class='input-group-text'>Adds Sidebar Bottom</span>
  </div>
  <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_sidebar_bottom', 'class' => 'form-control', 'value' => $data['adds_sidebar_bottom'], 'placeholder' => 'Sidebar Bottom Adds Code', 'rows' => '6')); ?>
</div>
