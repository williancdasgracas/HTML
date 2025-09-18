<?php
// Conecta-se ao banco de dados
$conn = new mysqli("localhost", "root", "", "relatorios", 3308);

// Verifica se há erros de conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se um arquivo foi enviado e se não há erros
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $nome = $_POST['nome'];
    $relatos = $_POST['ocorrencia'];
    $foto = $_FILES['foto'];

    // Define os tipos de arquivo permitidos e o tamanho máximo
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tamanho_maximo = 5 * 1024 * 1024; // 5 MB

    // Valida o tipo e o tamanho do arquivo
    if (in_array($foto['type'], $tipos_permitidos) && $foto['size'] <= $tamanho_maximo) {
        
        $pasta = "uploads/";
        $nomeFoto = uniqid() . "_" . basename($foto['name']);
        $caminho = $pasta . $nomeFoto;

        // Cria o diretório se ele não existir
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        // Move o arquivo enviado
        if (move_uploaded_file($foto['tmp_name'], $caminho)) {
            // Usa o prepared statement para inserir os dados
            $sql = "INSERT INTO ocorrencia (nome, relatos, imagem) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $relatos, $caminho);
            $stmt->execute();
            
            // Redireciona com uma mensagem de sucesso
            header("Location: index.html?status=success");
            exit;
        } else {
            // Redireciona com uma mensagem de erro
            header("Location: index.html?status=error_upload");
            exit;
        }
    } else {
        // Redireciona se o tipo ou tamanho do arquivo for inválido
        header("Location: index.html?status=error_file");
        exit;
    }
} else {
    // Redireciona se nenhum arquivo foi enviado
    header("Location: index.html?status=error_no_file");
    exit;
}

$conn->close();
?>