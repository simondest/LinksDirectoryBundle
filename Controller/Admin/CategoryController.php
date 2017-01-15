<?php
namespace Vertacoo\LinksDirectoryBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vertacoo\LinksDirectoryBundle\Form\Type\CategoryType;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CategoryController extends Controller
{

    public function listAction(Request $request, $page = 1)
    {
        $path = array();
        $parentId = $parent = null;
        
        $categoryManager = $this->container->get('vertacoo_links_directory.manager.category');
        
        if ($request->query->has('parentId')) {
            $parentId = $request->query->get('parentId');
            $parent = $this->findCategoryById($parentId);
            $path = $categoryManager->getCategoryPath($parent);
        }
        
        $maxCategoriesPerPage = $this->getParameter('vertacoo_links_directory.max_categories_per_page');
        
        $categoriesQuery = $categoryManager->getChildrenCategoriesQuery($parent, array(
            'leftNode'
        ), 'asc', $maxCategoriesPerPage, ($page - 1) * $maxCategoriesPerPage);
        
        $categories = new Paginator($categoriesQuery, false);
        
        $pagination = array(
            'page' => $page,
            'pages_count' => ceil(count($categories) / $maxCategoriesPerPage),
            'route' => 'vertacoo_links_directory_category_list',
            'route_params' => array('parentId'=>$parentId)
        );
        
        return $this->render('VertacooLinksDirectoryBundle:Admin/Category:list.html.twig', array(
            'path' => $path,
            'parent' => $parent,
            'parentId' => $parentId,
            'categories' => $categories,
            'pagination' => $pagination
        ));
    }

    public function showAction($id)
    {
        $category = $this->findCategoryById($id);
        $path = $this->container->get('vertacoo_links_directory.manager.category')->getCategoryPath($category);
        return $this->render('VertacooLinksDirectoryBundle:Admin/Category:show.html.twig', array(
            'path' => $path,
            'category' => $category
        ));
    }

    /**
     * Create a new category: show the new form.
     */
    public function newAction(Request $request)
    {
        $parentId = $parent = $path = null;
        $categoryManager = $this->container->get('vertacoo_links_directory.manager.category');
        if ($request->query->has('parentId')) {
            $parentId = $request->query->get('parentId');
            $parent = $this->findCategoryById($parentId);
            $path = $categoryManager->getCategoryPath($parent);
        }
        
        $category = $categoryManager->createCategory($parent);
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $categoryManager->updateCategory($category);
                $this->addFlash('success', $this->get('translator')
                    ->trans('admin.category.flash.created', array(), 'vertacoo_links_directory'));
                $parentId = $category->getParent() ? $category->getParent()->getId() : null;
                return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_category_list', array(
                    'parentId' => $parentId
                )));
            } else {
                $this->addFlash('danger', $this->get('translator')
                    ->trans('admin.category.flash.error', array(), 'vertacoo_links_directory'));
            }
        }
        
        return $this->render('VertacooLinksDirectoryBundle:Admin/Category:new.html.twig', array(
            'parentId' => $parentId,
            'form' => $form->createView(),
            'path' => $path
        ));
    }

    /**
     * Edit a category: show the edit form.
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->findCategoryById($id);
        $parentId = $category->getParent() ? $category->getParent()->getId() : null;
        
        $categoryManager = $this->container->get('vertacoo_links_directory.manager.category');
        $path = $categoryManager->getCategoryPath($category);
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if($form->isValid()){
            $this->container->get('vertacoo_links_directory.manager.category')->updateCategory($category);
            $this->addFlash('success', $this->get('translator')
                ->trans('admin.category.flash.updated', array(), 'vertacoo_links_directory'));
            
            return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_category_list', array(
                'parentId' => $parentId
            )));
            } else {
                $this->addFlash('danger', $this->get('translator')
                    ->trans('admin.category.flash.error', array(), 'vertacoo_links_directory'));
            }
        }
        return $this->render('VertacooLinksDirectoryBundle:Admin/Category:edit.html.twig', array(
            'category' => $category,
            'parentId' => $parentId,
            'form' => $form->createView(),
            'path' => $path
        ));
    }

    /**
     * Delete a category.
     */
    public function deleteAction($id)
    {
        $category = $this->findCategoryById($id);
        $parentId = $category->getParent() ? $category->getParent()->getId() : null;
        
        $this->container->get('vertacoo_links_directory.manager.category')->deleteCategory($category);
        $this->addFlash('success', $this->get('translator')
            ->trans('admin.category.flash.deleted', array(), 'vertacoo_links_directory'));
        
        return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_category_list', array(
            'parentId' => $parentId
        )));
    }

    /**
     * Move a category.
     */
    public function moveAction(Request $request, $id)
    {
        $parentId = null;
        $categoryManager = $this->container->get('vertacoo_links_directory.manager.category');
        $category = $this->findCategoryById($id);
        if ($request->query->has('parentId')) {
            $parentId = $request->query->get('parentId');
        }
        if ($request->query->has('direction')) {
            $direction = $request->query->get('direction');
            if ($direction == 'up') {
                if (! $parentId) {
                    $category->setPosition($category->getPosition() - 1);
                } else {}
                $categoryManager->moveCategoryUp($category);
            } else {
                if (! $parentId) {
                    $category->setPosition($category->getPosition() + 1);
                } else {}
                $categoryManager->moveCategoryDown($category);
            }
            
            $this->container->get('vertacoo_links_directory.manager.category')->updateCategory($category);
        }
        return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_category_list', array(
            'parentId' => $parentId
        )));
    }

    /**
     * Finds a category by id.
     *
     * @param mixed $id            
     *
     * @return CategoryInterface
     *
     * @throws NotFoundHttpException When category does not exist
     */
    protected function findCategoryById($id)
    {
        $category = $this->container->get('vertacoo_links_directory.manager.category')->findCategoryBy(array(
            'id' => $id
        ));
        if (null === $category) {
            throw new NotFoundHttpException(sprintf('The category with id %s does not exist', $id));
        }
        return $category;
    }
}
