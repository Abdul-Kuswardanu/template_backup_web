<main>
    <div class="container-fluid">
        <h1 class="mt-4">Update Profil</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active">Update Profil</li>
        </ol>

        <!-- Alert Section -->
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-4 text-center">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <ul class="list-unstyled mt-3 mb-4">
                            <img src="<?php echo base_url(); ?>assets/img/admin.avif" class="rounded" width="200px" alt="">
                            <hr>
                            <li><?php echo $user->email; ?></li>
                            <hr>
                            <li><?php echo $user->phone ?></li>
                        </ul>
                        <a href="<?php echo site_url(); ?>/profil/update_profil" class="btn btn-block btn-primary">Edit</a>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Profil</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" width="100%" cellspacing="0">
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>:</td>
                                <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                            </tr>
                            <tr>
                                <th>Kontak</th>
                                <td>:</td>
                                <td><?php echo $user->phone ?></td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>:</td>
                                <td><?php echo $user->username ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>:</td>
                                <td><?php echo $user->email ?></td>
                            </tr>
                            <tr>
                                <th>Level User</th>
                                <td>:</td>
                                <td><?php echo $user->level_users ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>