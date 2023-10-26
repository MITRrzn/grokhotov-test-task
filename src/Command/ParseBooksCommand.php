<?php

namespace App\Command;

use App\Service\BookService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:parse-books',
    description: 'Add a short description for your command',
)]
class ParseBooksCommand extends Command
{

    private const BOOKS_URL = 'https://gitlab.grokhotov.ru/hr/yii-test-vacancy/-/raw/master/books.json';

    public function __construct(private readonly BookService $bookService)
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('START COMMAND');

        $books = $this->parseBooks();

        foreach ($books as $book) {
            if (!array_key_exists('isbn', $book)) {
                $io->warning('Book ' . $book['title'] . ' dont have isbn');
                continue;
            }
            if (!$this->bookService->isBookExist($book['isbn'])) {
                $this->bookService->saveBook($book);
            }
        }

        $io->success('success');
        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function parseBooks(): array
    {
        $client = new Client();
        try {
            $request = $client->get(self::BOOKS_URL);
        } catch (GuzzleException) {
            throw new Exception('failed parse required url');
        }

        $data = $request->getBody()->getContents();

        return json_decode($data, true);
    }
}
