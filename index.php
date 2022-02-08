<!doctype html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Lista Telefonica</title>
</head>

<body class="bg-dark text-light">
    <div class="container">
        <div class="jumbotron bg-danger">

            <h1>Lista Telefonica</h1>
            <p>Sistema de agenda utilizando o CRUD com PHP e SQL</p>
        </div>

        <?php
        session_start();

        //CONNECTION BY PDO;
        $pdo = new PDO('mysql:host=localhost;dbname=lista-telefonica', 'root', '');

        if (isset($_POST["enviar"])) {

            //UPDATE;
            if (isset($_SESSION['id'])) {
                $pdo->exec("UPDATE `lista` SET `nome` = '" . $_POST['nome'] . "', `telefone` = '" . $_POST['telefone'] . "' WHERE `lista`.`id` =" . $_SESSION['id'] . ";");

                unset($_SESSION['id']);

                echo '<div class="alert alert-info">
            <strong>Sucesso!</strong> Lançamento editado com exito.
            </div>';
            }
            //INSERT;
            else {
                $sql = $pdo->prepare("INSERT INTO lista VALUES (null,?,?)");
                $sql->execute(array($_POST['nome'], $_POST['telefone']));
                echo '<div class="alert alert-success">
            <strong>Sucesso!</strong> Lançamento salvo com exito.
            </div>';
            }
        }

        //DELETE;
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $pdo->exec("DELETE FROM lista WHERE id=$id");
            echo '<div class="alert alert-danger">
        <strong>Sucesso!</strong> Lançamento excluido com exito.
        </div>';
        }

        //Check for the existence of the variable $_GET['id'];
        if (isset($_GET['edit'])) {

            //If it exists, it searches the values in the database;
            $id = (int)$_GET['edit'];
            $_SESSION['id'] = $id;

            $sql = $pdo->prepare("SELECT * FROM list WHERE id=" . $id);
            $sql->execute();

            $fetchlist = $sql->fetchAll();

            //Create INPUTs with values;
            foreach ($fetchlist as $key => $value) {
                echo '<form action=' . $_SERVER['PHP_SELF'] . ' method="POST"><div class="form-row">';
                echo '<div class="col-md-3 mb-3"><input type="text" class="form-control" name="nome" value ="' . $value['nome'] . '"></div>';
                echo '<div class="col-md-3 mb-3"><input type="text" class="form-control" name="telefone" value ="' . $value['telefone'] . '"></div>';
            }
        } else {
            // If it not exists, it creates the INPUTs without querying the database;
            echo '<form action=' . $_SERVER['PHP_SELF'] . ' method="POST"><div class="form-row">';
            echo '<div class="col-md-3 mb-3"><input type="text" class="form-control" name="nome" placeholder="Nome"></div>';
            echo '<div class="col-md-3 mb-3"><input type="text" class="form-control" name="telefone" placeholder="Telefone" ></div>';
        }
        ?>
        </form>
        <div class="col-md-3 mb-3"><input class="btn btn-secondary" type="submit" name="enviar" value="SALVAR"></div>
    </div>
    <table class="table bg-light mt-3">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>

        <?php
        //SELECT
        $sql = $pdo->prepare("SELECT * FROM lista");
        $sql->execute();

        $fetchlist = $sql->fetchAll();

        //Create table with values;
        foreach ($fetchlist as $key => $value) {
            echo '<tr><td>' . $value['nome'] . '</td><td>' . $value['telefone'] . '</td>
        <td><a class="btn btn-danger" href="?delete=' . $value['id'] . '">DELETAR</a>
        <a class="btn btn-info" href="?edit=' . $value['id'] . '">EDITAR</a></td></tr>';
        }
        ?>
        </div>
</body>

</html>