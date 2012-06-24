<?php
class AppException extends Exception
{
}

class RecordNotFoundException extends AppException
{
}

class PermissionDeniedException extends AppException
{
}
