import HeroSlider from "../components/home/HeroSlider";
import PopularCategories from "../components/home/PopularCategories";
import FeaturedProducts from "../components/home/FeaturedProducts";
import { Link } from "@inertiajs/react";
import LatestProducts from "../components/home/LatestProducts";
// import CustomerStories from "../components/home/CustomerStories";

const HomePage = ({sliders, categories, typeBaseProducts, homeProducts}) => {
    return (
        <>
            <HeroSlider sliders={sliders} />
            <PopularCategories categories={categories}/>
             {typeBaseProducts?.featured_product?.length > 0 && (
                 
                 <FeaturedProducts 
                    products={typeBaseProducts?.featured_product}
                />
            )}
             <LatestProducts products={homeProducts} />
        </>
    );
};

export default HomePage;
