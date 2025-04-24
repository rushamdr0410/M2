<?php
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// Get tmdb_id and media_type from URL parameters
$tmdb_id = isset($_GET['tmdb_id']) ? $_GET['tmdb_id'] : null;
$media_type = isset($_GET['media_type']) ? $_GET['media_type'] : 'movie';

if (!$tmdb_id) {
    header("Location: index.php");
    exit();
}

// TMDb API Configuration
$tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc';
$tmdb_base_url = 'https://api.themoviedb.org/3';

// Function to fetch trailer
function fetch_trailer($tmdb_id, $media_type, $tmdb_api_key) {
    $tmdb_base_url = 'https://api.themoviedb.org/3';
    $videos_url = "{$tmdb_base_url}/{$media_type}/{$tmdb_id}/videos?api_key={$tmdb_api_key}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $videos_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $videos_data = json_decode($response, true);
    
    $trailer = null;
    if (isset($videos_data['results']) && !empty($videos_data['results'])) {
        // First, try to find official trailer
        foreach ($videos_data['results'] as $video) {
            if ($video['site'] === 'YouTube' && 
                $video['type'] === 'Trailer' && 
                strtolower($video['name']) === 'official trailer') {
                $trailer = $video;
                break;
            }
        }
        
        // If no official trailer, look for any trailer
        if (!$trailer) {
            foreach ($videos_data['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer = $video;
                    break;
                }
            }
        }
        
        // If still no trailer, take any YouTube video
        if (!$trailer && !empty($videos_data['results'])) {
            foreach ($videos_data['results'] as $video) {
                if ($video['site'] === 'YouTube') {
                    $trailer = $video;
                    break;
                }
            }
        }
    }
    
    return $trailer;
}

// Function to fetch movie/TV show details
function fetch_details($tmdb_id, $media_type, $tmdb_api_key) {
    $tmdb_base_url = 'https://api.themoviedb.org/3';
    $details_url = "{$tmdb_base_url}/{$media_type}/{$tmdb_id}?api_key={$tmdb_api_key}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Fetch trailer and details
$trailer = fetch_trailer($tmdb_id, $media_type, $tmdb_api_key);
$details = fetch_details($tmdb_id, $media_type, $tmdb_api_key);
$title = $media_type === 'tv' ? $details['name'] : $details['title'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Trailer - <?php echo htmlspecialchars($title); ?></title>
    <link rel="website icon" type="JPG" href="#">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .title {
            color: #61DAFB;
            font-size: 1.5em;
            margin-bottom: 15px;
            text-align: left;
            padding-left: 10px;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-color: #111;
            margin-bottom: 20px;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .no-trailer {
            text-align: center;
            padding: 40px;
            font-size: 1.2em;
            color: #666;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .title {
                font-size: 1.2em;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($trailer): ?>
            <h1 class="title"><?php echo htmlspecialchars($title); ?></h1>
            <div class="video-container">
                <iframe
                    src="https://www.youtube.com/embed/<?php echo $trailer['key']; ?>?autoplay=1&rel=0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        <?php else: ?>
            <div class="no-trailer">
                <p>No trailer available for this <?php echo $media_type === 'tv' ? 'TV show' : 'movie'; ?>.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 