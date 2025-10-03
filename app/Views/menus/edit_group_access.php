<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('menus') ?>">Menu Management</a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('menus/group-access') ?>">Group Access</a></li>
               <li class="breadcrumb-item active">Edit Access</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Group: <strong><?= $groupName ?></strong></h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" onclick="selectAll()">
                        <i class="fas fa-check-double"></i> Select All
                     </button>
                     <button type="button" class="btn btn-tool" onclick="deselectAll()">
                        <i class="fas fa-times"></i> Deselect All
                     </button>
                  </div>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <div class="row">
                        <?php
                        $currentGroup = '';
                        foreach ($menuItems as $item):
                           if ($item['group_name'] != $currentGroup):
                              $currentGroup = $item['group_name'];
                        ?>
                        <div class="col-12">
                           <h5 class="mt-3 mb-2">
                              <i class="<?= $item['group_icon'] ?>"></i>
                              <?= $currentGroup ?>
                           </h5>
                           <hr>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-4 col-lg-3">
                           <div class="form-group">
                              <div class="custom-control custom-checkbox">
                                 <input class="custom-control-input menu-checkbox" type="checkbox"
                                    id="menu_<?= $item['id'] ?>" name="menu_items[]" value="<?= $item['id'] ?>"
                                    <?= in_array($item['id'], $accessibleMenuIds) ? 'checked' : '' ?>>
                                 <label class="custom-control-label" for="menu_<?= $item['id'] ?>">
                                    <i class="<?= $item['icon'] ?>"></i>
                                    <?= $item['name'] ?>
                                    <br>
                                    <small class="text-muted">
                                       <code><?= $item['url'] ?></code> |
                                       <span class="badge badge-info"><?= $item['permission'] ?></span>
                                    </small>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Access Rights
                     </button>
                     <a href="<?= base_url('menus/group-access') ?>" class="btn btn-default">Cancel</a>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
function selectAll() {
   document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
      checkbox.checked = true;
   });
}

function deselectAll() {
   document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
      checkbox.checked = false;
   });
}
</script>
<?= $this->endSection() ?>