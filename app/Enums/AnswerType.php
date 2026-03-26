<?php

namespace App\Enums;

enum AnswerType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case YesNo = 'yes_no';
}
