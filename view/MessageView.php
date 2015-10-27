<?php

namespace view;

class Message {

	// Error messages
	public static $missingTitle = "Title is missing";
	public static $missingArtist = "Artist is missing";
	public static $missingReleaseYear = "Release year is missing";
	public static $wrongReleaseYear = "Release year has to be in the format YYYY and can not be in the future.";
	public static $missingDescription = "Description is missing";
	public static $wrongPrice = "The price is wrong";
	public static $invalidFileType = "Invalid file type";
	public static $imageUploadError = "Problem with image upload";
	public static $generalError = "Sorry! Something went wrong";
	public static $stringIsTooLong = "The input must not exceed 140 characters";
	public static $fileSizeError = "The image file size can not exceed 1 MB";
	public static $unallowedCharacters = "Input contains invalid characters";

	// Success messages
	public static $hasBeenDeleted = " has been deleted";
	public static $hasBeenUpdated = "The record has been edited";
	public static $hasBeenAdded = "You have successfully added new record";
}