<?php
// لو جاينا طلب AJAX نستعمل API Procolis
if (isset($_GET['action']) && $_GET['action'] === 'Colis') {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    // رقم التتبع
    $tracking = "EC368WBA";

    // Endpoint الصحيح (GET)
    $url = "http://ecom-dz.net/Api_v1/Colis/Tracking/" . $tracking;

    $headers = [
        "token: cc46b9f1-2808-436b-bfe2-35a360e85f83",
        "key: 1ef8aef099624adda53cfb5098414fe4"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => curl_error($ch)]);
    } else {
        echo $response;
    }

    curl_close($ch);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Test API Procolis - Lire un Colis</title>
</head>
<body>
  <h2>Test API Procolis - Lire un Colis par Tracking</h2>
  <button onclick="callApi()">Lire le colis</button>
  <pre id="result">En attente...</pre>

  <script>
    async function callApi() {
      try {
        const response = await fetch("?action=Colis");
        const data = await response.json();
        document.getElementById("result").textContent = JSON.stringify(data, null, 2);
      } catch (error) {
        document.getElementById("result").textContent = "Erreur: " + error;
      }
    }
  </script>
</body>
</html>
