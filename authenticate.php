<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// tentativo di connessione al data base usando le info sopra
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// Se si verifica un errore con la connessione, interrompere lo script e visualizzare l'errore.
	exit('connessione a MySQL fallita: ' . mysqli_connect_error());
}
// Ora controlliamo se i dati dal modulo di accesso sono stati inviati, isset() verificherà se i dati esistono.
if ( !isset($_POST['username'], $_POST['password']) ) {

	exit('Si prega di compilare entrambi i campi nome utente e password!');
}
// Prepara il nostro SQL, la preparazione dell'istruzione SQL impedirà l'iniezione SQL.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parametri (s = string, i = int, b = blob, ecc), nel nostro caso il nome utente è una stringa quindi usiamo "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	//Memorizza il risultato in modo da poter verificare se l'account esiste nel database.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // L'account esiste, ora verifichiamo la password
        // Nota: ricorda di utilizzare password_hash nel tuo file di registrazione per memorizzare le password con hash.
        if (password_verify($_POST['password'], $password)) {
            // Verifica riuscita! L'utente ha effettuato l'accesso!
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            echo 'benvenuto ' . '!';
        } else {
            // password sbagliata
            echo 'Nome utente e/o password errati!';
        }
    } else {
        // username sbagliata
        echo 'Nome utente e/o password errati!';
    }


	$stmt->close();
}
?>

<html><body><a href="logout.php">logout</a></body></html>