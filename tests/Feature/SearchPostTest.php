<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SearchPostTest extends TestCase
{
    const TOTAL_POSTS = 100;
    const TEST_POSTS = 80;
    private Collection $testingPosts;
    private User $user;
    private string $searchString;

    protected function setUp(): void
    {
        parent::setUp();

        Post::factory()->count(self::TOTAL_POSTS)->create();
        $this->user = User::factory()->create();
        $this->searchString = $this->faker->regexify('[A-Za-z0-9]{20}');
        $this->testingPosts = Post::inRandomOrder()->take(self::TEST_POSTS)->get();
        $this->testingPosts->each(
            fn(Post $post) => $this->injectSearchStringInPostsField($post, $this->faker->boolean ? 'title' : 'body')
        );

        \DB::commit();
    }

    public function test_search_returns_correct_quantity_of_results_for_authenticated_user(): void
    {
        $countOfPostsWithModifiedTitle = count($this->occurrencesInField('title', $this->testingPosts->toArray()));
        $countOfPostsWithModifiedBody = count($this->testingPosts) - $countOfPostsWithModifiedTitle;

        $response = $this->actingAs($this->user)->callSearchPostsEndpointWithQuery($this->searchString);

        $response->assertOk();
        $response->assertJsonCount(self::TEST_POSTS);
        $this->assertCount($countOfPostsWithModifiedTitle, $this->occurrencesInField('title', $response->json()));
        $this->assertCount($countOfPostsWithModifiedBody, $this->occurrencesInField('body_excerpt', $response->json()));
    }

    public function test_search_returns_correct_quantity_of_results_for_unauthenticated_user(): void
    {
        $publicPosts = $this->testingPosts->where('is_public', true)->toArray();
        $countOfPublicPostsWithModifiedTitle = count($this->occurrencesInField('title', $publicPosts));
        $countOfPublicPostsWithModifiedBody = count($publicPosts) - $countOfPublicPostsWithModifiedTitle;

        $response = $this->callSearchPostsEndpointWithQuery($this->searchString);

        $response->assertOk();
        $response->assertJsonCount(count($publicPosts));
        $this->assertCount($countOfPublicPostsWithModifiedTitle,
            $this->occurrencesInField('title', $response->json()));
        $this->assertCount($countOfPublicPostsWithModifiedBody,
            $this->occurrencesInField('body_excerpt', $response->json()));
    }

    private function injectSearchStringInPostsField(Post $post, string $field): void
    {
        $post->$field = $this->replaceRandomWordBy($post->$field, $this->searchString);
        $post->save();
    }

    private function occurrencesInField(string $field, array $haystack): array
    {
        return array_filter(\Arr::pluck($haystack, $field),
            fn(string $value) => str_contains($value, $this->searchString));
    }

    private function replaceRandomWordBy(string $inputText, string $replacement): string
    {
        $words = explode(' ', $inputText);
        $randomWordIndex = array_rand($words);
        $words[$randomWordIndex] = $replacement;
        return implode(' ', $words);
    }

    private function callSearchPostsEndpointWithQuery(string $query = ''): TestResponse
    {
        return $this->getJson($this->getEndpointUrl() . '?search=' . $query);
    }

    private function getEndpointUrl(): string
    {
        return config('app.api_url') . '/posts';
    }
}
