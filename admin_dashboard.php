<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <script>
        function deleteUser(userid) {
            if (confirm('Sei sicuro di voler eliminare questo utente?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_user.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            var row = document.getElementById('row-' + userid);
                            if (row) {
                                row.parentNode.removeChild(row);
                            }
                        } else {
                            alert('Errore durante l\'eliminazione dell\'utente.');
                        }
                    }
                };
                xhr.send('id=' + encodeURIComponent(userid));
            }
        }

        function editUser(userid) {
            var row = document.getElementById('row-' + userid);
            var cells = row.querySelectorAll('.editable');

            cells.forEach(function (cell) {
                var originalText = cell.innerText;

                if (cell.querySelector('input[type="checkbox"]')) {
                    var checkbox = cell.querySelector('input[type="checkbox"]');
                    checkbox.disabled = false;  // Abilita il checkbox per la modifica
                } else {
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.value = originalText;
                    input.dataset.original = originalText;
                    input.dataset.field = cell.dataset.field;
                    input.dataset.id = cell.dataset.id;
                    cell.innerHTML = '';
                    cell.appendChild(input);
                }
            });

            var actionCell = row.querySelector('td:last-child');
            actionCell.innerHTML = `
            <button onclick='confirmEdit(${userid})'>Conferma</button>
            <button onclick='cancelEdit(${userid})'>Annulla</button>
        `;
        }

        function confirmEdit(userid) {
            var row = document.getElementById('row-' + userid);
            var inputs = row.querySelectorAll('input[type="text"], input[type="checkbox"]');

            var data = {};
            inputs.forEach(function (input) {
                if (input.type === 'checkbox') {
                    data[input.dataset.field] = input.checked ? 1 : 0;
                } else {
                    data[input.dataset.field] = input.value;
                }
            });
            data['userid'] = userid;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'edit_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        inputs.forEach(function (input) {
                            var cell = input.parentNode;
                            if (input.type === 'checkbox') {
                                cell.innerHTML = `<input data-field='${input.dataset.field}' type='checkbox' disabled ${input.checked ? 'checked' : ''}>`;
                            } else {
                                cell.innerHTML = input.value;
                            }
                        });
                        row.querySelector('td:last-child').innerHTML = `
                        <button onclick='editUser(${userid})'>Modifica</button>
                        <button onclick='deleteUser(${userid})'>Elimina</button>
                    `;
                    } else {
                        alert('Errore durante l\'aggiornamento.');
                    }
                }
            };
            xhr.send(Object.keys(data).map(key => `${key}=${encodeURIComponent(data[key])}`).join('&'));
        }

        function cancelEdit(userid) {
            var row = document.getElementById('row-' + userid);
            var inputs = row.querySelectorAll('input[type="text"], input[type="checkbox"]');

            inputs.forEach(function (input) {
                var cell = input.parentNode;
                if (input.type === 'checkbox') {
                    input.disabled = true; // Disabilita il checkbox
                } else {
                    cell.innerText = input.dataset.original;
                }
            });

            row.querySelector('td:last-child').innerHTML = `
            <button onclick='editUser(${userid})'>Modifica</button>
            <button onclick='deleteUser(${userid})'>Elimina</button>
        `;
        }

        function deleteProfessional(piva) {
            if (confirm('Sei sicuro di voler eliminare questo professionista?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_professional.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            var row = document.getElementById('row-prof-' + piva);
                            if (row) {
                                row.parentNode.removeChild(row);
                            }
                        } else {
                            alert('Errore durante l\'eliminazione del professionista.');
                        }
                    }
                };
                xhr.send('id=' + encodeURIComponent(piva));
            }
        }

        function editProfessional(piva) {
            var row = document.getElementById('row-prof-' + piva);
            var cells = row.querySelectorAll('.editable');

            cells.forEach(function (cell) {
                var originalText = cell.innerText;

                // Seleziona dropdown per 'professione'
                if (cell.querySelector('select')) {
                    var select = cell.querySelector('select');
                    select.disabled = false;
                }
                else if (cell.querySelector('input[type="checkbox"]')) {
                    var checkbox = cell.querySelector('input[type="checkbox"]');
                    checkbox.disabled = false;
                }
                else {
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.value = originalText;
                    input.dataset.original = originalText;
                    input.dataset.field = cell.dataset.field;
                    input.dataset.id = cell.dataset.id;
                    cell.innerHTML = '';
                    cell.appendChild(input);
                }
            });

            var actionCell = row.querySelector('td:last-child');
            actionCell.innerHTML = `
        <button onclick='confirmEditProfessional(${piva})'>Conferma</button>
        <button onclick='cancelEditProfessional(${piva})'>Annulla</button>
    `;
        }

        function confirmEditProfessional(piva) {
            var row = document.getElementById('row-prof-' + piva);
            var inputs = row.querySelectorAll('input[type="text"], select, input[type="checkbox"]');

            var data = {};
            inputs.forEach(function (input) {
                if (input.type === 'checkbox') {
                    data[input.dataset.field] = input.checked ? 1 : 0;
                } else {
                    data[input.dataset.field] = input.value;
                }
            });
            data['piva'] = piva;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'edit_professional.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        inputs.forEach(function (input) {
                            var cell = input.parentNode;
                            console.log(input.value);
                            if (input.tagName.toLowerCase() === 'select') {
                                cell.innerHTML = `<select  data-field='professione' disabled>
                                            <option value='Idraulico' ${input.value == 'Idraulico' ? 'selected' : ''}>Idraulico</option>
                                            <option value='Elettricista' ${input.value == 'Elettricista' ? 'selected' : ''}>Elettricista</option>
                                            <option value='Fabbro' ${input.value == 'Fabbro' ? 'selected' : ''}>Fabbro</option>
                                            <option value='Pittore' ${input.value == 'Pittore' ? 'selected' : ''}>Pittore</option>
                                            <option value='Colf' ${input.value == 'Colf' ? 'selected' : ''}>Colf</option>
                                            <option value='Tuttofare' ${input.value == 'Tuttofare' ? 'selected' : ''}>Tuttofare</option>
                                        </select>`;
                            } else if (input.type === 'checkbox') {
                                cell.innerHTML = `<input  data-field='is_active' type='checkbox' disabled ${input.checked ? 'checked' : ''}>`;
                            } else {
                                cell.innerHTML = input.value;
                            }
                        });
                        row.querySelector('td:last-child').innerHTML = `
                    <button onclick='editProfessional(${piva})'>Modifica</button>
                    <button onclick='deleteProfessional(${piva})'>Elimina</button>
                `;
                    } else {
                        alert('Errore durante l\'aggiornamento.');
                    }
                }
            };
            xhr.send(Object.keys(data).map(key => `${key}=${encodeURIComponent(data[key])}`).join('&'));
        }

        function cancelEditProfessional(piva) {
            var row = document.getElementById('row-prof-' + piva);
            var inputs = row.querySelectorAll('input[type="text"], select, input[type="checkbox"]');

            inputs.forEach(function (input) {
                var cell = input.parentNode;
                if (input.tagName.toLowerCase() === 'select' || input.type === 'checkbox') {
                    input.disabled = true;
                } else {
                    cell.innerText = input.dataset.original;
                }
            });

            row.querySelector('td:last-child').innerHTML = `
        <button onclick='editProfessional(${piva})'>Modifica</button>
        <button onclick='deleteProfessional(${piva})'>Elimina</button>
    `;
        }


    </script>
</head>

<body>
    <div class="top-bar">
        <div class="site-name">
            Homie - Admin Dashboard
        </div>
        <div class="wrapper">
            <div class="email-info">
                <?php
                echo htmlspecialchars($_SESSION['email']);
                ?>
            </div>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="table-section">
            <h2>Utenti Registrati</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Indirizzo</th>
                        <th>Admin</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'db_connection.php';

                    $query = "SELECT userid, nome, cognome, email, indirizzo FROM user_data";
                    $result = $conn->query($query);

                    if ($result === false) {
                        echo "Errore nella query: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $admin_query = "SELECT * FROM admin WHERE id = ?";
                                $admin_stmt = $conn->prepare($admin_query);
                                $admin_stmt->bind_param("i", $row['userid']);
                                $admin_stmt->execute();
                                $admin_result = $admin_stmt->get_result();

                                $isAdminChecked = ($admin_result->num_rows > 0) ? 'checked' : '';

                                echo "<tr id='row-{$row['userid']}'>
                                        <td>{$row['userid']}</td>
                                        <td class='editable' data-field='nome' data-id='{$row['userid']}'>{$row['nome']}</td>
                                        <td class='editable' data-field='cognome' data-id='{$row['userid']}'>{$row['cognome']}</td>
                                        <td class='editable' data-field='email' data-id='{$row['userid']}'>{$row['email']}</td>
                                        <td class='editable' data-field='indirizzo' data-id='{$row['userid']}'>{$row['indirizzo']}</td>
                                        <td class='editable' data-id='{$row['userid']}'>
                                            <input data-field='is_admin' type='checkbox' disabled {$isAdminChecked}>
                                        </td>
                                        <td>
                                            <button onclick='editUser({$row['userid']})'>Modifica</button>
                                            <button onclick='deleteUser({$row['userid']})'>Elimina</button>
                                        </td>
                                    </tr>";

                                $admin_stmt->close();
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nessun utente trovato</td></tr>";
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>

        </div>

        <div class="table-section">
            <h2>Professionisti Registrati</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Indirizzo</th>
                        <th>Professione</th>
                        <th>Prezzo Orario</th>
                        <th>Prezzo Chiamata</th>
                        <th>Rating</th>
                        <th>Attivo</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'db_connection.php';

                    $query = "SELECT piva, nome, cognome, email, indirizzo, professione, prezzo_orario, prezzo_chiamata, rating, is_active FROM pro_data";
                    $result = $conn->query($query);

                    if ($result === false) {
                        echo "Errore nella query: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr id='row-prof-{$row['piva']}'>
                                    <td>{$row['piva']}</td>
                                    <td class='editable' data-field='nome' data-id='{$row['piva']}'>{$row['nome']}</td>
                                    <td class='editable' data-field='cognome' data-id='{$row['piva']}'>{$row['cognome']}</td>
                                    <td class='editable' data-field='email' data-id='{$row['piva']}'>{$row['email']}</td>
                                    <td class='editable' data-field='indirizzo' data-id='{$row['piva']}'>{$row['indirizzo']}</td>
                                    <td class='editable' data-field='professione' data-id='{$row['piva']}'>
                                        <select data-field='professione' disabled>
                                            <option value='Idraulico' " . ($row['professione'] == 'Idraulico' ? 'selected' : '') . ">Idraulico</option>
                                            <option value='Elettricista' " . ($row['professione'] == 'Elettricista' ? 'selected' : '') . ">Elettricista</option>
                                            <option value='Fabbro' " . ($row['professione'] == 'Fabbro' ? 'selected' : '') . ">Fabbro</option>
                                            <option value='Pittore' " . ($row['professione'] == 'Pittore' ? 'selected' : '') . ">Pittore</option>
                                            <option value='Colf' " . ($row['professione'] == 'Colf' ? 'selected' : '') . ">Colf</option>
                                            <option value='Tuttofare' " . ($row['professione'] == 'Tuttofare' ? 'selected' : '') . ">Tuttofare</option>
                                        </select>
                                    </td>
                                    <td class='editable' data-field='prezzo_orario' data-id='{$row['piva']}'>{$row['prezzo_orario']}</td>
                                    <td class='editable' data-field='prezzo_chiamata' data-id='{$row['piva']}'>{$row['prezzo_chiamata']}</td>
                                    <td class='editable' data-field='rating' data-id='{$row['piva']}'>{$row['rating']}</td>
                                    <td class='editable' data-field='is_active' data-id='{$row['piva']}'>
                                        <input data-field='is_active' type='checkbox' disabled " . ($row['is_active'] == 1 ? 'checked' : '') . ">
                                    </td>
                                    <td>
                                        <button onclick='editProfessional({$row['piva']})'>Modifica</button>
                                        <button onclick='deleteProfessional({$row['piva']})'>Elimina</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>Nessun professionista trovato</td></tr>";
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>


        <div class="table-section">
            <h2>Ordini</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Ordine</th>
                        <th>ID Utente</th>
                        <th>ID Professionista</th>
                        <th>Data Ordine</th>
                        <th>Rating</th>
                        <th>Dettagli</th>
                        <th>Accettato</th>
                        <th>Completato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require 'db_connection.php';

                    $query = "SELECT order_id, user_id, pro_id, date, rating, details, accepted, completed FROM orders";
                    $result = $conn->query($query);

                    if ($result === false) {
                        echo "Errore nella query: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $accepted = $row['accepted'] ? 'Sì' : 'No';
                                $completed = $row['completed'] ? 'Sì' : 'No';

                                echo "<tr id='row-order-{$row['order_id']}'>
                                    <td>{$row['order_id']}</td>
                                    <td>{$row['user_id']}</td>
                                    <td>{$row['pro_id']}</td>
                                    <td>{$row['date']}</td>
                                    <td>{$row['rating']}</td>
                                    <td>{$row['details']}</td>
                                    <td>{$accepted}</td>
                                    <td>{$completed}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Nessun ordine trovato</td></tr>";
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="admin_dashboard.js"></script>
</body>

</html>