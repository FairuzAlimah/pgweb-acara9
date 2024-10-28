<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WebGIS Kabupaten Sleman</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 600px;
        }

        .title {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0;
            color: #ffff;
            padding: 10px 0;
            border-radius: 5px;
            background-color: rgba(0, 102, 153, 0.8);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            color: #ffff;
            padding: 10px 0;
            border-radius: 5px;
            background-color: rgba(0, 102, 153, 0.8);
        }


        .button {
            display: inline-block;
            padding: 10px 10px;
            background-color: #006699;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        /* Memposisikan button di tengah dan memberikan jarak vertikal */
        .button-container {
            text-align: center;
            margin: 30px 0;
            /* Jarak atas dan bawah */
        }

        /* Efek hover pada button */
        .button:hover {
            background-color: #004466;
        }
    </style>
</head>

<body>
    <h1 class="title">WebGIS - KAPANEWON DAERAH YOGYAKARTA</h1>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.761324006844154, 110.30906628007445], 10);

        // Tile Layer Base Map
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        osm.addTo(map);
    </script>

    <script>
        <?php
        // Koneksi ke database dan ambil data GeoJSON
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pgweb-acara8";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);
        $geojson = ['type' => 'FeatureCollection', 'features' => []];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["kecamatan"];
                $geojson['features'][] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$long, (float)$lat]
                    ],
                    'properties' => ['info' => $info]
                ];
            }
        } else {
            echo "console.log('Tidak ada data ditemukan');";
        }
        $conn->close();

        
        ?>
        

        var geojsonData = <?php echo json_encode($geojson); ?>;
        L.geoJSON(geojsonData, {
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng).bindTooltip(feature.properties.info, {
                    sticky: true,
                    direction: 'top'
                });
            }
        }).addTo(map);
    </script>

    <div class="table-title">Daftar Jumlah Penduduk Kapanewon Yogyakarta</div>

    <table>
        <tr>
            <th>Kecamatan</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Luas</th>
            <th>Jumlah Penduduk</th>
            <th>Aksi</th>
        </tr>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['kecamatan']}</td>
                    <td>{$row['longitude']}</td>
                    <td>{$row['latitude']}</td>
                    <td>{$row['luas']}</td>
                    <td align='right'>{$row['jumlah_penduduk']}</td>
                    <td>
                        <a href='edit.php?id={$row['id']}'>Edit</a> |
                        <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <div class="button-container">
        <a href="index.html" class="button">Tambah Data Baru</a>
    </div>
</body>

</html>