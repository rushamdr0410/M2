<?php
// assume you have a function to update the like count in your database
function updateLikeCount($reviewId) {
  // update the like count in your database
  // return the new like count
  $newLikeCount = 10; // replace with actual logic
  return $newLikeCount;
}

if (isset($_POST['reviewId'])) {
  $reviewId = $_POST['reviewId'];
  $newLikeCount = updateLikeCount($reviewId);
  echo json_encode(array('likes' => $newLikeCount));
  exit;
} else {
  echo 'Error: reviewId not set';
  exit;
}