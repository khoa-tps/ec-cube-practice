## EC-CUBE 4.3 – Ghi chú custom nhanh

### 1. Thêm cột mới vào Entity + DB (ví dụ `Product.description_detail_english`)

1. **Tạo trait trong `app/Customize/Entity`**

   ```php
   namespace Customize\Entity;

   use Doctrine\ORM\Mapping as ORM;
   use Eccube\Annotation\EntityExtension;

   /**
    * @EntityExtension("Eccube\Entity\Product")
    */
   trait ProductTrait
   {
       /**
        * @ORM\Column(name="description_detail_english", type="string", length=255, nullable=true)
        */
       private $description_detail_english;

       public function getDescriptionDetailEnglish(): ?string
       {
           return $this->description_detail_english;
       }

       public function setDescriptionDetailEnglish(?string $v): self
       {
           $this->description_detail_english = $v;

           return $this;
       }
   }
   ```

2. **Tạo wrapper Entity (không sửa core)**

   ```php
   namespace Customize\Entity;

   use Eccube\Entity\Product as BaseProduct;

   class Product extends BaseProduct
   {
       use ProductTrait;
   }
   ```

3. **Generate proxy + migration + migrate**

   ```bash
   php bin/console eccube:generate:proxies
   php bin/console doctrine:migrations:diff
   php bin/console doctrine:migrations:migrate
   ```

---

### 2. Mở rộng Form (thêm field vào form admin Product)

1. **Tạo FormTypeExtension trong `app/Customize/Form/Extension/Admin`**

   ```php
   namespace Customize\Form\Extension\Admin;

   use Eccube\Common\EccubeConfig;
   use Eccube\Form\Type\Admin\ProductType;
   use Symfony\Component\Form\AbstractTypeExtension;
   use Symfony\Component\Form\Extension\Core\Type\TextareaType;
   use Symfony\Component\Form\FormBuilderInterface;
   use Symfony\Component\Validator\Constraints as Assert;

   class ProductTypeExtension extends AbstractTypeExtension
   {
       protected $eccubeConfig;

       public function __construct(EccubeConfig $eccubeConfig)
       {
           $this->eccubeConfig = $eccubeConfig;
       }

       public function buildForm(FormBuilderInterface $builder, array $options)
       {
           $builder->add('description_detail_english', TextareaType::class, [
               'required' => false,
               'purify_html' => true,
               'constraints' => [
                   new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
               ],
           ]);
       }

       public static function getExtendedTypes(): iterable
       {
           return [ProductType::class];
       }
   }
   ```

   > Lưu ý: không override lại các field core (như `Category`) trừ khi thật sự cần; để tránh lỗi kiểu dữ liệu không khớp.

2. **Hiển thị field trong admin template override**

   - Copy template gốc:
     - Từ: `src/Eccube/Resource/template/admin/Product/product.twig`
     - Sang: `app/template/admin/Product/product.twig`

   - Thêm block render field, ví dụ dưới mô tả chi tiết:

   ```twig
   {{ form_widget(form.description_detail_english, { attr : { rows : '8' } }) }}
   {{ form_errors(form.description_detail_english) }}
   ```

3. **Clear cache**

   ```bash
   php bin/console cache:clear
   ```

---

### 3. Ghi đè / thêm Controller

- **Không sửa controller trong `src/Eccube/Controller`**.
- Tạo controller mới trong `app/Customize/Controller`:

  ```php
  namespace Customize\Controller;

  use Eccube\Controller\ProductController as BaseProductController;
  use Symfony\Component\Routing\Annotation\Route;

  class ProductController extends BaseProductController
  {
      /**
       * @Route(\"/products/custom/{id}\", name=\"product_custom_detail\", methods={\"GET\"})
       */
      public function customDetail(int $id)
      {
          // custom logic
      }
  }
  ```

- `app/config/eccube/routes.yaml` đã load `app/Customize/Controller` với `type: annotation`, nên chỉ cần đặt đúng namespace + `@Route`.

---

### 4. Ghi đè / thêm Twig template

- **Override**: copy từ core sang `app/template` với cùng cấu trúc thư mục.
  - Ví dụ: chi tiết sản phẩm front
    - Gốc: `src/Eccube/Resource/template/default/Product/detail.twig`
    - Override: `app/template/default/Product/detail.twig`
  - Trong bản override có thể dùng field mới:

    ```twig
    <div class=\"ec-productRole__description\">{{ Product.description_detail|raw|nl2br }}</div>
    {% if Product.description_detail_english is defined and Product.description_detail_english is not empty %}
    <div class=\"ec-productRole__description\">{{ Product.description_detail_english|raw|nl2br }}</div>
    {% endif %}
    ```

- **Template mới**: tạo file `.twig` trong `app/template/<theme>/...` và render từ controller (`@Template` hoặc `$this->render()`).

---

### 5. Các lệnh hữu ích khi debug / tìm route, form, entity

- **List route (giống `php artisan route:list`)**  
  ```bash
  php bin/console debug:router
  ```

- **Xem entity mapping sau khi thêm trait**  
  ```bash
  php bin/console eccube:generate:proxies
  ```

- **Clear cache**  
  ```bash
  php bin/console cache:clear
  ```

---

### 6. Ghi đè / mở rộng Plugin (Controller, Twig, Entity)

#### 6.1. Nguyên tắc chung

- **Không sửa trực tiếp** mã plugin trong `app/Plugin/<PluginCode>/` khi có thể tránh:
  - Dễ bị mất khi update plugin.
  - Khó merge khi nâng cấp.
- Cách ưu tiên:
  - Dùng **hook/event** do plugin cung cấp.
  - Dùng **template snippet** / override template ở `app/template`.
  - Chỉ sửa plugin trực tiếp nếu chấp nhận tự maintain fork.

#### 6.2. Kiểm tra plugin đang làm gì

1. Mã plugin nằm ở: `app/Plugin/<PluginCode>/`.
2. Các entry point chính:
   - `PluginManager.php`: định nghĩa template snippet, hook, entity, v.v.
   - `Controller/` và `Controller/Admin/`: controller front + admin plugin.
   - `Resource/template/default/` và `Resource/template/admin/`: twig của plugin.
   - `Form/Type/`: FormType riêng của plugin.
   - `Entity/`: entity plugin (thường được khai báo qua `PluginManager`).

#### 6.3. Override Twig của plugin

- Nhiều plugin (ví dụ `ProductReview42`) **chèn snippet** vào trang core thông qua event:
  - Ví dụ: `ProductReviewEvent::onRenderProductDetail()` gọi  
    `addSnippet('ProductReview42/Resource/template/default/review.twig')`.
- Bạn có 2 cách can thiệp:

1. **Chỉnh trực tiếp twig trong plugin (dễ nhưng kém an toàn khi update)**  
   - Sửa file: `app/Plugin/<PluginCode>/Resource/template/.../*.twig`.

2. **Bao lại bằng template của mình** (khuyến nghị hơn khi plugin hỗ trợ):
   - Tạo twig mới trong `app/template/<theme>/...` và:
     - Thay đổi nơi gọi snippet (nếu event cho phép).
     - Hoặc thêm HTML bổ sung quanh khu vực snippet (ví dụ dựa trên `id`/`class` mà plugin render).

> Ghi chú: Cách override 100% twig plugin (giống override core) phụ thuộc plugin có dùng `@PluginCode/...` làm tên template hay không; nếu có, có thể ghi đè bằng cách đặt twig cùng tên trong một namespace do bạn cấu hình thêm. Mặc định EC-CUBE không tạo sẵn namespace override cho plugin.

#### 6.4. Gọi / hiểu Controller của plugin

- Controllers plugin nằm trong `app/Plugin/<PluginCode>/Controller/`:
  - Dùng `@Route` như controller core.
  - Ví dụ `ProductReview42`:

    ```php
    /**
     * @Route("/product_review/{id}/review", name="product_review_index", requirements={"id" = "\d+"})
     */
    public function index(Request $request, Product $Product) { ... }
    ```

- Từ twig, plugin (hoặc bạn) gọi bằng:

  ```twig
  {{ url('product_review_index', { id: Product.id }) }}
  ```

- Để xem plugin có những route nào:

  ```bash
  php bin/console debug:router | grep ProductReview42
  # hoặc grep theo tên route plugin, ví dụ: product_review_
  ```

#### 6.5. Mở rộng Entity của plugin

Nếu plugin khai báo entity riêng (ví dụ `Plugin\ProductReview42\Entity\ProductReview`):

1. **Thêm trait y như cách mở rộng core**:

   - Tạo trait trong `app/Customize/Entity`:

     ```php
     namespace Customize\Entity;

     use Doctrine\ORM\Mapping as ORM;
     use Eccube\Annotation\EntityExtension;

     /**
      * @EntityExtension("Plugin\ProductReview42\Entity\ProductReview")
      */
     trait ProductReviewExtensionTrait
     {
         /**
          * @ORM\Column(name="extra_note", type="string", length=255, nullable=true)
          */
         private $extra_note;
     }
     ```

   - Không cần tạo wrapper class nếu chỉ dùng trait; EC-CUBE sẽ tạo proxy cho entity plugin.

2. **Regenerate proxies + migration cho plugin entity**

   ```bash
   php bin/console eccube:generate:proxies
   php bin/console doctrine:migrations:diff
   php bin/console doctrine:migrations:migrate
   ```

> Lưu ý: khi diff, Doctrine sẽ so toàn bộ schema (core + plugin). Đảm bảo plugin đã enable và entity đã được `PluginManager` đăng ký.

#### 6.6. Thay đổi luồng xử lý plugin (cẩn trọng)

- Nếu cần thay đổi mạnh luồng xử lý (ví dụ thay đổi logic trong `ProductReviewController`):
  - Cách “an toàn” nhất là:
    1. **Đọc mã controller plugin** để hiểu route, form, template.
    2. Tạo controller riêng trong `app/Customize/Controller` với route **khác** (ví dụ `/product_review/custom/...`) và dùng logic của bạn.
    3. Sửa twig (core hoặc plugin) để link tới route mới của bạn thay vì route gốc của plugin.
- Tránh ghi đè controller plugin bằng cách dùng lại cùng URL + name, trừ khi bạn nắm rõ toàn bộ tác dụng phụ (event, hook, setting admin). 

---

### 7. Event/Hook trong login (front + admin)

#### 7.1. "Event" và "Hook" trong EC-CUBE là gì?

- Trong EC-CUBE, "hook" thường là cách gọi chung cho điểm mở rộng.
- Về kỹ thuật, phần lớn hook được triển khai bằng **Symfony EventDispatcher**:
  - Controller `dispatch(...)` một event.
  - Plugin/custom code đăng ký listener/subscriber để bắt event đó.

#### 7.2. Các event login quan trọng có sẵn

1. **Trước khi render form login admin**
   - Event: `EccubeEvents::ADMIN_ADMIM_LOGIN_INITIALIZE`
   - Nơi phát: `AdminController::login()`
   - Mục đích: can thiệp `FormBuilder` login admin trước khi tạo form view.

2. **Trước khi render form login mypage**
   - Event: `EccubeEvents::FRONT_MYPAGE_MYPAGE_LOGIN_INITIALIZE`
   - Nơi phát: `MypageController::login()`
   - Mục đích: custom form login ở mypage.

3. **Trước khi render form login khi checkout**
   - Event: `EccubeEvents::FRONT_SHOPPING_LOGIN_INITIALIZE`
   - Nơi phát: `ShoppingController::login()`
   - Mục đích: custom form login ở luồng mua hàng.

4. **Sau khi login thành công (security-level)**
   - Event Symfony: `SecurityEvents::INTERACTIVE_LOGIN`
   - EC-CUBE đang bắt event này ở:
     - `SecurityListener` (update login date, merge cart cho Customer, v.v.)
     - `LoginHistoryListener` (ghi login history cho admin).

5. **Khi login thất bại (security-level)**
   - Event Symfony: `LoginFailureEvent::class`
   - EC-CUBE dùng để:
     - Lưu trạng thái "remember me" (`SecurityListener`).
     - Ghi login history thất bại cho admin (`LoginHistoryListener`).

#### 7.3. Khi nào nên bắt event nào?

- Muốn **thêm field/validate vào form login**: bắt event `...LOGIN_INITIALIZE` tương ứng.
- Muốn **chạy logic sau khi auth thành công** (log, sync, tracking): bắt `SecurityEvents::INTERACTIVE_LOGIN`.
- Muốn **xử lý login fail** (audit, chống brute-force bổ sung): bắt `LoginFailureEvent::class`.

#### 7.4. Ví dụ subscriber bắt login success/failure

Tạo file: `app/Customize/EventSubscriber/LoginSubscriber.php`

```php
namespace Customize\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onLoginSuccess',
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onLoginSuccess(InteractiveLoginEvent $event): void
    {
        // TODO: logic sau login thành công
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        // TODO: logic khi login thất bại
    }
}
```

> `app/config/eccube/services.yaml` đã autowire/autoconfigure cho namespace `Customize\`, nên subscriber thường tự được nhận diện.

