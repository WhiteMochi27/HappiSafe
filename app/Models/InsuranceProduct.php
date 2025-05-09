<?

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'base_price',
        'happi_coins_reward',
        'coverage_details',
        'policy_details',
        'duration_days',
        'policy_document_path',
        'is_featured',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'coverage_details' => 'array',
        'policy_details' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(InsuranceCategory::class, 'category_id');
    }

    public function userInsurances()
    {
        return $this->hasMany(UserInsurance::class);
    }
}
