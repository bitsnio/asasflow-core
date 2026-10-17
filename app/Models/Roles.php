    <?php

    namespace Modules\Core\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Roles extends Model
    {
        use SoftDeletes;

        protected $fillable = [
            // @asasflow:generated-fillable:start
            // @asasflow:generated-fillable:end
        ];

        protected $casts = [
            // @asasflow:generated-casts:start
            // @asasflow:generated-casts:end
        ];
    }
