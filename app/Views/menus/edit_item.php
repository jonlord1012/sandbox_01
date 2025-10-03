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
               <li class="breadcrumb-item"><a href="<?= base_url('menus/items') ?>">Menu Items</a></li>
               <li class="breadcrumb-item active">Edit Item</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Edit Menu Item</h3>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <?php if (isset($validation)): ?>
                     <div class="alert alert-danger">
                        <?= $validation->listErrors() ?>
                     </div>
                     <?php endif; ?>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="name">Menu Name *</label>
                              <input type="text" class="form-control" id="name" name="name"
                                 value="<?= old('name', $item['name']) ?>" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="icon">Icon Class *</label>
                              <input type="text" class="form-control" id="icon" name="icon"
                                 value="<?= old('icon', $item['icon']) ?>" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="url">URL Path *</label>
                              <input type="text" class="form-control" id="url" name="url"
                                 value="<?= old('url', $item['url']) ?>" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="permission">Shield Permission *</label>
                              <input type="text" class="form-control" id="permission" name="permission"
                                 value="<?= old('permission', $item['permission']) ?>" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="menu_group_id">Menu Group *</label>
                              <select class="form-control" id="menu_group_id" name="menu_group_id" required>
                                 <option value="">Select Group</option>
                                 <?php foreach ($menuGroups as $group): ?>
                                 <option value="<?= $group['id'] ?>"
                                    <?= old('menu_group_id', $item['menu_group_id']) == $group['id'] ? 'selected' : '' ?>>
                                    <?= $group['name'] ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="parent_id">Parent Menu</label>
                              <select class="form-control" id="parent_id" name="parent_id">
                                 <option value="">No Parent (Top Level)</option>
                                 <?php foreach ($parentMenus as $parent): ?>
                                 <option value="<?= $parent['id'] ?>"
                                    <?= old('parent_id', $item['parent_id']) == $parent['id'] ? 'selected' : '' ?>>
                                    <?= $parent['name'] ?>
                                 </option>
                                 <?php endforeach; ?>
                              </select>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="order_number">Order Number *</label>
                              <input type="number" class="form-control" id="order_number" name="order_number"
                                 value="<?= old('order_number', $item['order_number']) ?>" min="0" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group" style="margin-top: 32px;">
                              <div class="custom-control custom-switch">
                                 <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" <?= old('is_active', $item['is_active']) ? 'checked' : '' ?>>
                                 <label class="custom-control-label" for="is_active">Active Menu Item</label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">Update Menu Item</button>
                     <a href="<?= base_url('menus/items') ?>" class="btn btn-default">Cancel</a>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>