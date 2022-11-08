<?php
include '../lib/db.php';

if (isset($_POST['add_event'])) {
    $event_name = mysqli_real_escape_string($connection, $_POST['event_name']);
    $event_description = mysqli_real_escape_string($connection, $_POST['event_description']);
    $event_date = mysqli_real_escape_string($connection, $_POST['event_date']);

    $query = mysqli_query($connection, "INSERT INTO events(event_name,event_description,event_date)
                        VALUES ('$event_name','$event_description','$event_date')");
    if ($query) {
        $notif = '<div class="alert alert-success">Berhasil Tambah Data</div>';
    } else {
        $notif = '<div class="alert alert-danger">Gagal Tambah Data</div>';
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = mysqli_query($connection, "DELETE FROM events WHERE id_event='$id'");
    if ($query) {
        $notifDelete = '<div class="alert alert-success">Berhasil Hapus Data</div>';
    } else {
        $notifDelete = '<div class="alert alert-danger">Gagal Hapus Data</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>


    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12">
                <h1 class="text-center">Dashboard Event</h1>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-7">
                <div class="card border-0 shadow-lg">
                    <?php
                    if (isset($notif)) {
                        echo $notif;
                    } else if(isset($notifDelete)){
                        echo $notifDelete;
                    }
                    ?>
                    <div class="card-body">
                        <button class=" btn btn-primary " data-toggle="modal" data-target="#modalevents">Tambah Event</button>
                        <a href="../index.php" class="btn btn-dark">Lihat Website</a>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Nama Event</th>
                                    <th>Deskripsi Event</th>
                                    <th>Tanggal Event</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($connection, "SELECT * FROM events");
                                while ($data = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $data['event_name'] ?></td>
                                        <td><?php echo $data['event_description'] ?></td>
                                        <td><?php echo $data['event_date'] ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button id="actionbtn" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-hashpopup="true" aria-expanded="false">
                                                    Action Button
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="actionbtn">
                                                    <a href="edit.php?edit=<?php echo $data['id_event'] ?>" class="dropdown-item">Edit</a>
                                                    <a href="dashboard.php?delete=<?php echo $data['id_event'] ?>" class="dropdown-item">Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>List User Event</h3>
                        </div>
                        <ul class="list-group">
                            <?php
                            $query = mysqli_query($connection,"SELECT * FROM user_event INNER JOIN events ON events.id_event=user_event.id_event");
                            while($data = mysqli_fetch_array($query)){
                            ?>
                            <li class="list-group-item"><span class="badge badge-primary"><?php echo $data['event_name'] ?></span> / <?php echo $data['fullname'] ?> / <?php echo $data['email'] ?></li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalevents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Event</label>
                            <input type="text" class="form-control" name="event_name" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Event</label>
                            <textarea name="event_description" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Event</label>
                            <input type="date" class="form-control" name="event_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_event">simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>