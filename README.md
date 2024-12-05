# Filterable Package

A custom Laravel package for dynamic filtering of models, built to simplify the process of filtering data in Laravel applications.

## Installation

1. **Install via Composer**

   Run the following command to add the package to your Laravel project:

   ```bash
   composer require janakkapadia/filterable
   ```

2. **Usage**

    This package provides a command to generate filter models dynamically.

    Creating a New Filter Model:

    ```bash
    php artisan filter:model {ModelName}
    ```
   
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

    Basic filtering:
    ```php
    public function index(Request $request)
    {
        $data = YourModel::filter()->get();
    }
    ```

    With parentheses wrapping where conditions:
    ```php
    public function index(Request $request)
    {
        $data = YourModel::filter(true)->get();
    }
    ```
   
5. **Request Example**

   To filter and sort the data, you can make a request like this:

   ```
   GET URL/your-model?sort_by=id&sort_order=desc&search=title
   ```

6. **Example Usage For Model Filter File**

    In your filter model (YourModelFilter.php), you can define filter methods:

    ```php
    class YourModelFilters extends Filter
    {
        protected array $filters = ['sort_by', 'search', 'status'];
   
        public function sort_by($column): void
        {
            $this->builder->orderBy($column, request('sort_order', 'asc'));
        }
   
        public function search($keyword): void
        {
            $this->builder->where(function ($query) use ($keyword) {
                $query->where('field1', 'like', "%$keyword%")
                    ->orWhere('field2', 'like', "%$keyword%");
            });
        }

        public function status($status): void
        {
            $this->builder->where('status', $status);
        }
    }
    ```

7. **Query Examples**

    Without parentheses (`filter()`):
    ```sql
    SELECT * FROM your_models 
    WHERE field1 LIKE '%keyword%' 
    OR field2 LIKE '%keyword%' 
    AND status = 'active'
    ORDER BY id DESC
    ```

    With parentheses (`filter(true)`):
    ```sql
    SELECT * FROM your_models 
    WHERE (field1 LIKE '%keyword%' OR field2 LIKE '%keyword%')
    AND (status = 'active')
    ORDER BY id DESC
    ```

    The parentheses version ensures proper grouping of conditions and can prevent unexpected results when combining multiple filters.

8. **Advanced Features**

   - **Parentheses Wrapping**: Use `filter(true)` to wrap where conditions in parentheses for complex queries
   - **Filter Chaining**: You can chain multiple filter operations together

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://buymeacoffee.com/janak.kapadia)