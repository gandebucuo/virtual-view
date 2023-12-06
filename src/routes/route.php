<?php
use VirtualCloud\Controllers\RedisController;

Route::get("/",[RedisController::class,'index']);
Route::get("redis_dbs_index",[RedisController::class,'redis_dbs_index']);
Route::get("redis_keys_index",[RedisController::class,'redis_keys_index']);
Route::get("redis_key_show",[RedisController::class,'redis_key_show']);
Route::get("redis_val_save",[RedisController::class,'redis_val_save']);
Route::get("redis_val_refresh",[RedisController::class,'redis_val_refresh']);
Route::get("redis_del",[RedisController::class,'redis_del']);
Route::get("redis_expire",[RedisController::class,'redis_expire']);

