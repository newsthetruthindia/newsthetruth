<?php
echo json_encode(\App\Models\User::with('details.media')->find(397)->toArray(), JSON_PRETTY_PRINT);
