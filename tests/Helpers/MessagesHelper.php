<?php

use CoRex\Support\Messages;

class MessagesHelper extends Messages
{
    // System.
    const SYSTEM_ERROR = [500, 'System error occurred'];
    const SYSTEM_NOT_FOUND = [404, 'Not found'];

    // Test.
    const SYSTEM_TEST = [200, 'Test ({param1}/{param2})'];
}