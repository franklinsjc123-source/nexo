<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>401 Unauthorized</title>
  <link rel="stylesheet" href="<?= asset('assets') ?>/css/bootstrap.min.css" />
  <style>
    body {
      background: linear-gradient(135deg, #2c3e50, #7a7b7c);
      color: #fff;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .error-container {
      text-align: center;
      padding: 3rem 2rem;
    }
    .error-code {
      font-size: 8rem;
      font-weight: 800;
      color: #f8f9fa;
      text-shadow: 2px 4px 8px rgba(0,0,0,0.4);
    }
    .error-message {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .error-details {
      font-size: 1rem;
      color: rgba(255,255,255,0.7);
      margin-bottom: 2rem;
    }
    .btn-custom {
      padding: 0.75rem 2rem;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 30px;
    }
  </style>
</head>
<body>

  <div class="error-container">
    <div class="error-code">401</div>
    <div class="error-message">Unauthorized Request</div>
    <div class="error-details">
      You may have mistyped the address or the page may have moved.<br>
      Try going back to the previous page.
    </div>
    <a class="btn btn-success btn-custom" href="javascript:window.history.back();">⬅ Back to Home</a>
  </div>

</body>
</html>
