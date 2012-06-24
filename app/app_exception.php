<?php
class AppException extends Exception
{
}

class AuthDeniedException extends AppException
{
}

class RecordNotFoundException extends AppException
{
}

class PermissionDeniedException extends AppException
{
}
