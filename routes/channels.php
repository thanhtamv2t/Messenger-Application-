<?php
Broadcast::channel('chat', function ($user) {
  return $user;
});
Broadcast::channel('friend_request_*', function ($user) {
  return $user;
});
Broadcast::channel('private_chat_*_*', function ($user) {
  return $user;
});