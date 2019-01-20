<?php

namespace spec\App\Exception;

use App\Exception\InvalidSelectionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InvalidSelectionExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InvalidSelectionException::class);
    }

    function it_is_a_kind_of_domain_exception()
    {
        $this->shouldHaveType(\DomainException::class);
    }
}
