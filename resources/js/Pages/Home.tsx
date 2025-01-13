import ProductItem from "@/Components/App/ProductItem";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, PaginationProps, Product } from "@/types";
import { Head, Link } from "@inertiajs/react";

export default function Home({
 products
}: PageProps<{products: PaginationProps<Product> }>) {
 

  return (
    <AuthenticatedLayout>
      <Head title="Home" />
      <div className="hero bg-gray-200 h-[300px]">
        <div className="hero-content flex-col lg:flex-row">
          <img
            src="https://img.daisyui.com/images/stock/photo-1635805737707-575885ab0820.webp"
            className="max-w-sm rounded-lg shadow-2xl"
          />
        
        </div>
      </div>
      <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 p-8">
        {products.data.map(product => (
          <ProductItem product = {product} key={product.id}/>
        ))}
      </div>
    </AuthenticatedLayout>
  );
}
