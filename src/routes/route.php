<?php
use VirtualCloud\Controllers\ViewController;

Route::get("/",[ViewController::class,'index']);
Route::get("redis_dbs_index",[ViewController::class,'redis_dbs_index']);
Route::get("redis_keys_index",[ViewController::class,'redis_keys_index']);
Route::get("redis_key_show",[ViewController::class,'redis_key_show']);
Route::get("redis_val_save",[ViewController::class,'redis_val_save']);
Route::get("redis_val_refresh",[ViewController::class,'redis_val_refresh']);
Route::get("redis_del",[ViewController::class,'redis_del']);
Route::get("redis_expire",[ViewController::class,'redis_expire']);

