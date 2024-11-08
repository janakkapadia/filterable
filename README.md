# Filterable Package

A custom Laravel package for dynamic filtering of models, built to simplify the process of filtering data in Laravel applications.

## Installation

1. **Install via Composer**

   Run the following command to add the package to your Laravel project:

   ```bash
   composer require janakkapadia/filterable

2. **Usage**

    This package provides a command to generate filter models dynamically.

    Creating a New Filter Model

    To create a new filter model, use the following command:

    ```bash
    php artisan filter:model {ModelName}
   
3. **Add Filter Trait to Your Model**
    
    ```php
    use JanakKapadia\Filterable\Traits\Filter;
    
    class YourModel extends Model
    {
        use Filter;
    
        // Additional model code...
    }
    ```

4. **Usage In Controller**
    ```php
    public function index(Request $request)
    {
        $data = YourModel::filter()->get();       
    }
    ```
   
5. **Request Example**

   To filter and sort the data, you can make a request like this:

   ```
   GET URL/your-model?sort_by=id&sort_order=desc&search=title
   ```
6. **Example Usage For Model Filter File**


      In your filter model (YourModelFilter.php), you can define a sort_by method:

      ```php
      class YourModelFilters extends Filter
      {
         protected array $filters = ['sort_by', 'search'];
   
         public function sort_by($column): void
         {
             $this->builder->orderBy($column, request('sort_order', 'asc'));
         }
   
         public  function search($keyword): array
         {
            $this->builder->where(function ($query) use ($keyword) {
                $query->where('field1', 'like', "%$keyword%")
                    ->orWhere('field2', 'like', "%$keyword%")
            })
         }
      }
      ```
   
7. This will filter by id in descending order also search in model.

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://buymeacoffee.com/janak.kapadia)