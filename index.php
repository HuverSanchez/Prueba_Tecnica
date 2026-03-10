<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "world");

// Cargar todos los países para el select
$paises = $conexion->query("SELECT Code, Name FROM country");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Ciudades</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Consulta de Ciudades por País</h2>

    <form method="GET">
        <select name="pais" required>
            <option value="">Selecciona un país</option>
            <?php while($p = $paises->fetch_assoc()): ?>
                <option value="<?php echo $p['Code']; ?>">
                    <?php echo $p['Name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <button type="submit">Ver ciudades</button>
    </form>

    <?php
    // Si el usuario selecciona un país, procesamos la consulta
    if (isset($_GET['pais'])) {
        $codigo = $_GET['pais'];

        // Top 10 ciudades más pobladas
        $ciudades_mayores = $conexion->query("
            SELECT Name, Population 
            FROM city 
            WHERE CountryCode = '$codigo' 
            ORDER BY Population DESC
            LIMIT 10
        ");

        // Top 10 ciudades menos pobladas
        $ciudades_menores = $conexion->query("
            SELECT Name, Population 
            FROM city 
            WHERE CountryCode = '$codigo' 
            ORDER BY Population ASC
            LIMIT 10
        ");

        // Tabla: ciudades más pobladas
        if ($ciudades_mayores->num_rows > 0) {

            echo "<h3>Top 10 ciudades más pobladas</h3>";
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Población</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            while ($c = $ciudades_mayores->fetch_assoc()) {
                echo "<tr>
                        <td>" . $c['Name'] . "</td>
                        <td>" . number_format($c['Population']) . "</td>
                      </tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }

        // Tabla: ciudades menos pobladas
        if ($ciudades_menores->num_rows > 0) {

            echo "<h3>Top 10 ciudades con menor población</h3>";
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Población</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            while ($c = $ciudades_menores->fetch_assoc()) {
                echo "<tr>
                        <td>" . $c['Name'] . "</td>
                        <td>" . number_format($c['Population']) . "</td>
                      </tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
    }
    ?>

</body>
</html>

