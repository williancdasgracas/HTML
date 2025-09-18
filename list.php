<?php
$conn = new mysqli("localhost", "root", "", "relatorios");

if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT nome, email, relatos, imagem, data_ocorrencia FROM ocorrencia ORDER BY data_ocorrencia DESC";
$result = $conn->query($sql);

echo "<h1>Relatos Recebidos</h1>";

while ($row = $result->fetch_assoc()) {
  echo "<div style='border:1px solid #ccc; padding:20px; margin:20px; max-width:600px;'>";
  echo "<strong>Nome:</strong> " . htmlspecialchars($row['nome']) . "<br>";
  echo "<strong>Data:</strong> " . $row['data_ocorrencia'] . "<br><br>";
  echo "<strong>Relato:</strong><br>" . nl2br(htmlspecialchars($row['relatos'])) . "<br><br>";
  echo "<img src='" . htmlspecialchars($row['imagem']) . "' width='300' style='border-radius:8px;'><br>";
  echo "</div>";
}

$conn->close();
?>