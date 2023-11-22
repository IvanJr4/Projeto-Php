<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = "#Ivanjr1227";
$dbname = "gerenciamentotarefas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

function listarTarefas($conn) {
    $sql = "SELECT id, descricao, data_limite, concluida FROM tarefas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Lista de Tarefas:</h2>";
        echo "<table border='1'><tr><th>ID</th><th>Descrição</th><th>Data Limite</th><th>Concluída</th></tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["id"]."</td><td>".$row["descricao"]."</td><td>".$row["data_limite"]."</td><td>".($row["concluida"] ? 'Sim' : 'Não')."</td></tr>";
        }

        echo "</table>";
    } else {
        echo "Nenhuma tarefa encontrada.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST["descricao"];
    $dataLimite = $_POST["data_limite"];

    // Verificar se a chave "concluida" existe e atribuir o valor adequado
    $concluida = isset($_POST["concluida"]) ? 1 : 0;

    $descricao = $conn->real_escape_string($descricao);
    $dataLimiteFormatada = date("Y-m-d", strtotime($dataLimite));

    $sql = "INSERT INTO tarefas (descricao, data_limite, concluida) VALUES ('$descricao', STR_TO_DATE('$dataLimiteFormatada', '%Y-%m-%d'), $concluida)";

    if ($conn->query($sql) === TRUE) {
        echo "Tarefa adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar tarefa: " . $conn->error;
    }
}

listarTarefas($conn);

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Tarefas</title>
</head>
<body>
    <h1>Gerenciamento de Tarefas</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="descricao">Descrição da Tarefa:</label>
        <input type="text" name="descricao" required>
        <br><br>
        <label for="data_limite">Data Limite (YYYY-MM-DD):</label>
        <input type="text" name="data_limite" required>
        <br><br>
        <label for="concluida">Concluída:</label>
        <input type="checkbox" name="concluida">
        <br><br>
        <input type="submit" value="Adicionar Tarefa">
    </form>
</body>
</html>