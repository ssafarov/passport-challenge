<?php

    use App\Models\Tree;
    use Illuminate\Database\Seeder;

class TreesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tree::create([
            'key' => 'cf23df2207d99a74fbe169e3eba035e633b65d94',
            'data' => '{"hash": "903d667b705c5ca4dee3f64d28ab3029", "title":"Root", "isRoot": true, "children":[{"hash": "b6acceb2678669b4be6eaf5bd5ab3d2c", "title": "Fabric #1", "lower":"5", "higher":"8", "children": []},{"hash": "7ca1eeb8aac81059b373de3ed78c6ae6", "title": "Fabric #2", "lower":"15", "higher":"80", "children": []},{"hash":"d86ef3e734583862033111ab26a2d1a3", "title": "Fabric #3", "lower":"100", "higher":"400", "children": [{"title": "100"},{"title": "200"},{"title": "300"}]},{"hash":"d8686e3111ab2862ef3e734586a2d1a3", "title": "Fabric #4", "lower":"100", "higher":"400", "children": [{"title": "110"},{"title": "210"},{"title": "310"}]},{"hash":"deFabric 34311862862586a2d3e786e11a1", "title": "Fabric #5", "lower":"120", "higher":"350", "children": [{"title": "120"},{"title": "220"},{"title": "320"}]}]}',
        ]);
    }
}
