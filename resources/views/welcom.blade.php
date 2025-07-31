<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Parking Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      background: linear-gradient(-135deg, #c850c0, #4158d0);
    }
    .custom-button {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 15px;
      line-height: 1.5;
      color: #fff;
      text-transform: uppercase;
      width: 70%;
      max-width: 300px;
      height: 45px;
      border-radius: 25px;
      background: #57b846;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0 25px;
      transition: all 0.4s;
      margin: 0 auto;
    }
    .custom-button:hover {
      background-color: #333333;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="bg-white bg-opacity-90 rounded-lg shadow-lg max-w-3xl w-full px-12 pt-16 pb-8 text-center">
    <h2 class="text-2xl mb-3 text-gray-900 font-poppins font-bold">Parking Management System</h2>
    <p class="text-base mb-5 text-gray-500 font-normal max-w-xl mx-auto">This system is designed to manage customers, vehicles, and parking subscriptions.</p>
    <a href="{{route('login')}}" class="custom-button">Log in</a>
  </div>
</body>
</html>
