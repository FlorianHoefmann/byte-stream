<?php

namespace Amp\ByteStream\Test\Base64;

use Amp\ByteStream\Base64\Base64EncodingInputStream;
use Amp\ByteStream\InputStream;
use Amp\ByteStream\PipelineStream;
use Amp\PHPUnit\AsyncTestCase;
use Amp\Pipeline\Emitter;
use function Amp\ByteStream\buffer;
use function Amp\launch;

class Base64EncodingInputStreamTest extends AsyncTestCase
{
    private Emitter $source;

    private InputStream $stream;

    public function testRead(): void
    {
        $future = launch(fn () => buffer($this->stream));

        $this->source->emit('f');
        $this->source->emit('o');
        $this->source->emit('o');
        $this->source->emit('.');
        $this->source->emit('b');
        $this->source->emit('a');
        $this->source->emit('r');
        $this->source->complete();

        self::assertSame('Zm9vLmJhcg==', $future->await());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = new Emitter;
        $this->stream = new Base64EncodingInputStream(new PipelineStream($this->source->asPipeline()));
    }
}
